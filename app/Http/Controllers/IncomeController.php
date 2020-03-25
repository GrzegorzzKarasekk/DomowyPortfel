<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Income;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    public function index()
    {
        $now = Carbon::now();   
        $today_date = $now->format('Y-m-d');
        $options = DB::table('users_incomes')->where('user_id', '=', Auth::id())->get(); 

        return view('incomes.index', compact('today_date', 'options'));
    }    
    /**
     * Create a new income instance after a valid added income.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(Request $request)
    {

        $now = Carbon::now();   
        $today_date = $now->format('Y-m-d');

        $this->validate($request,[
            'amount' => ['required', 'numeric', 'min:0.01'],
            'transaction_date' => ['required', 'max:255','date_format:Y-m-d', 'before_or_equal:today_date'],
        ]);
        
        $userId = Auth::id();
        //Dodawanie do bazy
        if(Income::create([
            'user_id' => $userId,
            'category_user_id' => $request['category_name'],
            'amount' => $request['amount'],
            'transaction_date' => $request['transaction_date'],
            'description' => $request['description'],            
        ]))
        {
            return redirect()->back()->with('success', 'DODAŁEŚ NOWY PRZYCHÓD!'); 
        }
        else
        return redirect()->back()->with('danger', 'NIE UDAŁO SIĘ DODAĆ PRZUCHODU :('); 
    }

    protected function editIncomeFromBalance(Request $request){
        
        $now = Carbon::now();   
        $today_date = $now->format('Y-m-d');

        $this->validate($request,[
            'transaction_date' => ['required', 'max:255','date_format:Y-m-d', 'before_or_equal:today_date'],
            'amount' => ['required', 'numeric', 'min:0.01'],            
            'category_name' => ['required'],
        ]);
        
        if(DB::table('incomes')
        ->where('id', '=', $request->incomeId)
        ->update([
            'category_user_id' => $request['category_name'],
            'amount' => $request['amount'],
            'transaction_date' => $request['transaction_date'],
            'description' => $request['description'],           
            
            ]))
        {
            return redirect()->back()->with('success', 'PRZYCHÓD ZAKTUALIZOWANY!'); 
        }
        else
            return redirect()->back()->with('danger', 'Problem z serwerem. REKORD NIE ZOSTAŁ ZAKTUALIZOWANY!'); 

    }


    protected function deleteIncomeFromBalance(Request $request){
        
        if(DB::table('incomes')->where('id', '=', $request->incomeId)->delete())
        {
            return redirect()->back()->with('success', 'PRZYCHÓD ZAKTUALIZOWANY!'); 
        }
        else
            return redirect()->back()->with('danger', 'Problem z serwerem. REKORD NIE ZOSTAŁ USUNIĘTY!'); 

    }

}
