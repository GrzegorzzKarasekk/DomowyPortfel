<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Income;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use DateTime;
//use Symfony\Component\Console\Input\Input;

class IncomeController extends Controller
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
            'amount' => ['required', 'numeric', 'min:0.00'],
            'transaction_date' => ['required', 'max:255','date_format:Y-m-d', 'before_or_equal:today_date'],
        ]);
        
        $userId = Auth::id();
        // $userId = Auth::user()->id;

        //Dodawanie do bazy
        Income::create([
            'user_id' => $userId,
            'category_user_id' => $request['category_name'],
            'amount' => $request['amount'],
            'transaction_date' => $request['transaction_date'],
            'description' => $request['description'],            
        ]);

        //return redirect($this->redirectPath());
        return redirect()->back()->with('success', 'DODAŁEŚ NOWY PRZYCHÓD!'); 
        //redirect()->back()->with('success', 'your message here');   
    }

    public function index()
    {
        $now = Carbon::now();   
        $today_date = $now->format('Y-m-d');
        $options = DB::table('users_incomes')->where('user_id', '=', Auth::id())->get(); 
        

        // foreach ($options as $option) {
        //     echo $option->id;
        //     echo $option->category_name;
            
        // }
        
        //var_dump($options);

        return view('incomes.index', compact('today_date', 'options'));
    }    




}

