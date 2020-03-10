<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Expense;
use App\Income;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
 
    public function index()
    {

        $userIncomes = DB::table('users_incomes')->where('user_id', '=', Auth::id())->get(); 
        $userExpenses = DB::table('users_expenses')->where('user_id', '=', Auth::id())->get(); 
        $userPaymentMethods = DB::table('users_payment_methods')->where('user_id', '=', Auth::id())->get(); 

        $userData = DB::table('users')->where('id', '=', Auth::id())->get(); 

        //echo $userData->id;
        // echo $userData->first();
        return view('sites.settings', compact('userIncomes','userExpenses', 'userPaymentMethods', 'userData'));
    }  

    protected function createNewIncomeNameCategory(Request $request){

        $newCategory = $request->newIncomeCategory;
        $allIncomeUserCategory = DB::table('users_incomes')->where('user_id', '=', Auth::id())->get();

        foreach($allIncomeUserCategory as $userCategory){

            if($userCategory->category_name == $newCategory)
                return redirect()->back()->with('danger', 'TAKA KATEGORIA ISTNIEJE!'); 
        }

        if(DB::table('users_incomes')->insertGetId(
            array('user_id' => Auth::id(), 'category_name' => $newCategory)
        ))
        {
            return redirect()->back()->with('success', 'DODAŁEŚ NOWĄ KATEGORIĘ!'); 
        }
        else
        return redirect()->back()->with('danger', 'NIE UDAŁO SIĘ DODAĆ KATEGORII, BRAK POŁĄCZENIA Z BAZĄ DANYCH'); 
        
    }

    protected function changeIncomeNameCategory(Request $request){

        if(DB::table('users_incomes')
        ->where('id', '=', $request->category_name )
        ->update([
            'category_name' => $request->newNameEditCategoryIncome   
            ]))
        {
            return redirect()->back()->with('success', 'KATEGORIA ZAKTUALIZOWANA!'); 
        }
        else
            return redirect()->back()->with('danger', 'Problem z serwerem. REKORD NIE ZOSTAŁ ZAKTUALIZOWANY!'); 
   
    }

    protected function deleteIncomeNameCategory(Request $request){

        //echo $request->category_nameIncomeId;
        if(DB::table('users_incomes')->where('id', '=', $request->category_nameIncomeId)->delete())
        {
            return redirect()->back()->with('success', 'KATEGORIA USUNIĘTA!'); 
        }
        else
            return redirect()->back()->with('danger', 'Problem z serwerem. REKORD NIE ZOSTAŁ USUNIĘTY!'); 
   
    }

    protected function createNewExpenseNameCategory(Request $request){

        $newCategory = $request->newExpenseCategory;
        $allExpenseUserCategory = DB::table('users_expenses')->where('user_id', '=', Auth::id())->get();

        foreach($allExpenseUserCategory as $userCategory){

            if($userCategory->category_name == $newCategory)
                return redirect()->back()->with('danger', 'TAKA KATEGORIA ISTNIEJE!'); 
        }

        if(DB::table('users_expenses')->insertGetId(
            array('user_id' => Auth::id(), 'category_name' => $newCategory)
        ))
        {
            return redirect()->back()->with('success', 'DODAŁEŚ NOWĄ KATEGORIĘ!'); 
        }
        else
        return redirect()->back()->with('danger', 'NIE UDAŁO SIĘ DODAĆ KATEGORII, BRAK POŁĄCZENIA Z BAZĄ DANYCH'); 
        
    }

    protected function changeExpenseNameCategory(Request $request){

        if(DB::table('users_expenses')
        ->where('id', '=', $request->category_nameE )
        ->update([
            'category_name' => $request->newNameEditCategoryExpense   
            ]))
        {
            return redirect()->back()->with('success', 'KATEGORIA ZAKTUALIZOWANA!'); 
        }
        else
            return redirect()->back()->with('danger', 'Problem z serwerem. REKORD NIE ZOSTAŁ ZAKTUALIZOWANY!'); 
   
    }

    protected function deleteExpenseNameCategory(Request $request){

        //echo $request->category_nameIncomeId;
        if(DB::table('users_expenses')->where('id', '=', $request->category_nameExpenseId)->delete())
        {
            return redirect()->back()->with('success', 'KATEGORIA USUNIĘTA!'); 
        }
        else
            return redirect()->back()->with('danger', 'Problem z serwerem. REKORD NIE ZOSTAŁ USUNIĘTY!'); 
   
    }

    protected function createNewPaymentMethodNameCategory(Request $request){

        $newCategory = $request->newPaymentMethodCategory;
        $allPaymentMethodUserCategory = DB::table('users_payment_methods')->where('user_id', '=', Auth::id())->get();

        foreach($allPaymentMethodUserCategory as $userPaymentMethod){

            if($userPaymentMethod->payment_method == $newCategory)
                return redirect()->back()->with('danger', 'TAKA KATEGORIA ISTNIEJE!'); 
        }

        if(DB::table('users_payment_methods')->insertGetId(
            array('user_id' => Auth::id(), 'payment_method' => $newCategory)
        ))
        {
            return redirect()->back()->with('success', 'DODAŁEŚ NOWĄ KATEGORIĘ SPOSOBU PŁATNOŚCI!'); 
        }
        else
        return redirect()->back()->with('danger', 'NIE UDAŁO SIĘ DODAĆ KATEGORII, BRAK POŁĄCZENIA Z BAZĄ DANYCH'); 
        
    }

    protected function changePaymentMethodNameCategory(Request $request){

        if(DB::table('users_payment_methods')
        ->where('id', '=', $request->category_namePayOption )
        ->update([
            'payment_method' => $request->newNameEditCategoryPaymentMethod   
            ]))
        {
            return redirect()->back()->with('success', 'KATEGORIA ZAKTUALIZOWANA!'); 
        }
        else
            return redirect()->back()->with('danger', 'Problem z serwerem. REKORD NIE ZOSTAŁ ZAKTUALIZOWANY!'); 
   
    }

    protected function deletePaymentMethodNameCategory(Request $request){

        //echo $request->category_nameIncomeId;
        if(DB::table('users_payment_methods')->where('id', '=', $request->category_namePaymentMethodId)->delete())
        {
            return redirect()->back()->with('success', 'KATEGORIA USUNIĘTA!'); 
        }
        else
            return redirect()->back()->with('danger', 'Problem z serwerem. REKORD NIE ZOSTAŁ USUNIĘTY!'); 
   
    }

    protected function changeTheUserData(Request $request){
        
        $request['name'] = mb_convert_case($request['name'],MB_CASE_TITLE,"UTF-8");
        
        $this->validate($request,[
            'name' => ['regex:/^[A-ZŁŚŻŹ]{1}[a-ząęółśżźćń]+$/i','required', 'string', 'between:3,255'],
            'password' => ['required', 'string', 'min:6', 'max:20', 'confirmed'],
        ]);

        if(DB::table('users')
        ->where('id', '=', Auth::id())
        ->update([
            'name' => $request->name,
            'password' => Hash::make($request['password']),  
            ]))
        {
            return redirect()->back()->with('success', 'TWOJE DANE ZOSTAŁY ZAKTUALIZOWANA!'); 
        }
        else
            return redirect()->back()->with('danger', 'Problem z serwerem. TWOJE DANE NIE ZOSTAŁY ZAKTUALIZOWANE!'); 
       
    }

    protected function deleteUser(Request $request){
        
        $usersData = DB::table('users')->where('id', '=', Auth::id())->get(); 

        if($usersData->count() == 1){

            foreach($usersData as $user){
                if(Hash::check($request->passwordDelete, $user->password))
                {
                    if(DB::table('users')->where('id', '=', $user->id)->delete())
                    {
                        DB::table('incomes')->where('id', '=', $user->id)->delete();
                        DB::table('expenses')->where('id', '=', $user->id)->delete();
                        DB::table('users_incomes')->where('id', '=', $user->id)->delete();
                        DB::table('users_expenses')->where('id', '=', $user->id)->delete();
                        DB::table('users_incomes')->where('id', '=', $user->id)->delete();
                        DB::table('users_payment_methods')->where('id', '=', $user->id)->delete();
                        DB::table('user_total_cost_of_expenses_in_time')->truncate();
                        

                        return redirect('/')->with('success', 'UŻYTKOWNIKU, ZOSTAŁEŚ USUNIĘTY!'); 
                    }
                    else
                        return redirect()->back()->with('danger', 'Problem z serwerem. NIE ZOSTAŁEŚ USUNIĘTY!'); 
                }
                else
                    return redirect()->back()->with('danger', 'Coś Poszło nie tak. SPRAWDŹ HASŁO!'); 
              }
        }
        else
            return redirect()->back()->with('danger', 'Problem z serwerem. NIE ZOSTAŁEŚ USUNIĘTY!');  
    }   

}

