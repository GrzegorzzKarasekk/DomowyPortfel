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

    public function editExpenseFromBilance(Request $request){
        
        $now = Carbon::now();   
        $today_date = $now->format('Y-m-d');
 
        $this->validate($request,[
            'transaction_date' => ['required', 'max:255','date_format:Y-m-d', 'before_or_equal:today_date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method_id' => ['required'],            
            'category_name' => ['required'],
        ]);

            // echo $request->expenseId;
            // echo '<br />';
            // echo $request->transaction_date;
            // echo '<br />';
            // echo $request->payment_method_id;
            // echo '<br />';
            // echo $request->category_name;
            // echo '<br />';
            // echo $request->description;
            // echo '<br />';

            if(DB::table('expenses')
        ->where('id', '=', $request->expenseId)
        ->update([
            'category_user_id' => $request['category_name'],
            'payment_method_id' => $request['payment_method_id'],
            'amount' => $request['amount'],
            'transaction_date' => $request['transaction_date'],
            'description' => $request['description'],           
            
            ]))
        {
            return redirect()->back()->with('success', 'WYDATEK ZAKTUALIZOWANY!'); 
        }
        else
        return redirect()->back()->with('danger', 'Problem z serwerem. REKORD NIE ZOSTAŁ ZAKTUALIZOWANY!'); 

    }



}

