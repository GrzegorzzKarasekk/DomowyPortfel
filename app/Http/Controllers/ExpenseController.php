<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Expense;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use DateTime;
//use Symfony\Component\Console\Input\Input;

class ExpenseController extends Controller
{
    
    /**
     * Create a new income instance after a valid added income.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(Request $request)
    {

        $dataczas = new DateTime();
        $today_date = $dataczas->format('Y-m-d');

        // echo $request['transaction_date'];
        // echo "</br>";
        // echo $today_date;

        $this->validate($request,[
            'pay_option' => ['required'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'transaction_date' => ['required', 'max:255','date_format:Y-m-d', 'before_or_equal:today_date'],
        ]);
        
        $userId = Auth::id();
        // $userId = Auth::user()->id;
        
        var_dump($request['pay_option']); 
        //Dodawanie do bazy
        
        
        if(Expense::create([
            'user_id' => $userId,
            'payment_method_id' => $request['pay_option'],
            'category_user_id' => $request['category_name'],
            'amount' => $request['amount'],
            'transaction_date' => $request['transaction_date'],
            'description' => $request['description'],            
        ]))
        {
            return redirect()->back()->with('success', 'DODAŁEŚ NOWY WYDATEK!'); 
            
        }
        else
            return redirect()->back()->with('danger', 'NIE UDAŁO SIĘ DODAĆ WYDATKU :('); 
        //redirect()->back()->with('success', 'your message here');   
    }

    public function index()
    {
        $now = Carbon::now();   
        $today_date = $now->format('Y-m-d');
        $options = DB::table('users_expenses')->where('user_id', '=', Auth::id())->get(); 
        $payoptions = DB::table('users_payment_methods')->where('user_id', '=', Auth::id())->get(); 
        //var_dump($payoptions);
        return view('expenses.index', compact('today_date', 'options', 'payoptions'));
    }    




}

