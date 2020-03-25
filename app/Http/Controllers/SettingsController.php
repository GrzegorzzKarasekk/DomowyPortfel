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

        return view('sites.settings', compact('userIncomes','userExpenses', 'userPaymentMethods', 'userData'));
    }  

    protected function checkTheIncomeName($newName){

        $newCategory1 = '';

        $deleteWitheChars = trim($newName); //Białe znaki

        $newCategory = mb_strtolower($deleteWitheChars, 'UTF-8'); //wszytkie litery małe
        $newCategory= mb_convert_case($newCategory, MB_CASE_TITLE, "UTF-8");

        $allIncomeUserCategory = DB::table('users_incomes')->where('user_id', '=', Auth::id())->get();

        foreach($allIncomeUserCategory as $userCategory){

            if($userCategory->category_name == $newCategory)
                return $newCategory1;
        }
        return $newCategory;
    }

    protected function createNewIncomeNameCategory(Request $request){

        $newName = $request->newIncomeCategory;
        
        $newCategory = SettingsController::checkTheIncomeName($newName);

        if($newCategory == '')
        {
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

        $newName = $request->newNameEditCategoryIncome;
        
        $newCategoryName  = SettingsController::checkTheIncomeName($newName);

        if($newCategoryName == '')
        {
            return redirect()->back()->with('danger', 'TAKA KATEGORIA ISTNIEJE!');
        }
        
        if(DB::table('users_incomes')
        ->where('id', '=', $request->category_name )
        ->update([
            'category_name' => $newCategoryName 
            ]))
        {
            return redirect()->back()->with('success', 'KATEGORIA ZAKTUALIZOWANA!'); 
        }
        else
            return redirect()->back()->with('danger', 'Problem z serwerem. REKORD NIE ZOSTAŁ ZAKTUALIZOWANY!'); 
    }

    protected function deleteIncomeNameCategory(Request $request){

        $howManyRecords = DB::table('incomes')->where('category_user_id', '=', $request->category_nameIncomeId, 'and','user_id', '=' ,Auth::id())->get();
        
        if($howManyRecords->count() >  0) 
        {
            return redirect()->back()->with('info', 'ABY USUNĄĆ  TĄ  KATEGORIĘ NAJPIERW EDYTUJ LUB USUŃ WSZYSTKIE REKORDY PRZYPISANE TEJ KATEGORII!');
        }
        else{
            if(DB::table('users_incomes')->where('id', '=', $request->category_nameIncomeId)->delete())
            {
                return redirect()->back()->with('success', 'KATEGORIA USUNIĘTA!'); 
            }
            else
                return redirect()->back()->with('danger', 'Problem z serwerem. REKORD NIE ZOSTAŁ USUNIĘTY!'); 
        }
    }

    protected function checkTheExpenseName($newName){

        $newCategory1= '';

        $deleteWitheChars = trim($newName); //Białe znaki

        $newCategory = mb_strtolower($deleteWitheChars, 'UTF-8'); //wszytkie litery małe
        $newCategory= mb_convert_case($newCategory, MB_CASE_TITLE, "UTF-8");

        $allExpenseUserCategory = DB::table('users_expenses')->where('user_id', '=', Auth::id())->get();

        foreach($allExpenseUserCategory as $userCategory){

            if($userCategory->category_name == $newCategory)
                return $newCategory1; 
        }
        return $newCategory;
    }

    protected function createNewExpenseNameCategory(Request $request){

        $newName = $request->newExpenseCategory;

        $newCategory =  SettingsController::checkTheExpenseName($newName);
        if($newCategory == ''){
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

        $start = Carbon::now();   
        $today_date = $start->format('Y-m-d');
        $firstDay = $start->firstOfMonth()->format('Y-m-d');
        $lastDay = $start->lastOfMonth()->format('Y-m-d');

        if(isset($request->newNameEditCategoryExpense))
        {
            $newName = $request->newNameEditCategoryExpense;
            $newCategoryName = SettingsController::checkTheExpenseName($newName);

            if($newCategoryName == ''){
                return redirect()->back()->with('danger', 'TAKA KATEGORIA ISTNIEJE!');
            }
        }

        if (isset($newCategoryName) && isset($request->limit)){

            if(DB::table('users_expenses')->where('id', '=', $request->category_nameE )->update([
                'category_name' => $newCategoryName,
                'limit' => $request->limit
            ])){
                return redirect()->back()->with('success', 'KATEGORIA ZAKTUALIZOWANA!'); 
            }else
                return redirect()->back()->with('danger', 'Problem z serwerem. REKORD NIE ZOSTAŁ ZAKTUALIZOWANY!'); 
        }
        elseif (isset($newCategoryName) && !(isset($request->limit))){

            if(DB::table('users_expenses')->where('id', '=', $request->category_nameE )->update([
                'category_name' => $newCategoryName
            ])){
                return redirect()->back()->with('success', 'KATEGORIA ZAKTUALIZOWANA!'); 
            }else
                return redirect()->back()->with('danger', 'Problem z serwerem. REKORD NIE ZOSTAŁ ZAKTUALIZOWANY!'); 
        }
        elseif (!(isset($newCategoryName)) && isset($request->limit)){

            if(DB::table('users_expenses')->where('id', '=', $request->category_nameE )->update([
                'limit' => $request->limit
            ])){
                return redirect()->back()->with('success', 'KATEGORIA ZAKTUALIZOWANA!'); 
            }else
                return redirect()->back()->with('danger', 'Problem z serwerem. REKORD NIE ZOSTAŁ ZAKTUALIZOWANY!'); 
        }
        else{
            return redirect()->back();
        }
    }

    protected function showCategoryLimit(Request $request){
        
        $results = DB::table('users_expenses')->where('id', '=', $request->categoryId)->get();
        $liczbaWierszy = $results->count();
        if( $liczbaWierszy == 1){
            foreach($results as $result){
                $limit = $result->limit;
            }
        }
        if(isset($limit)){
            echo 'Limit wynosi: ', number_format($limit, 2);
        }
        else{
            echo 'Brak limitu dla wybranej kategorii';
        }
    }

    protected function deleteLimitCategory(Request $request){
        
        if(DB::table('users_expenses')->where('id', '=', $request->categoryId)->update([
            'limit' => NULL
        ])){
            return redirect()->back()->with('success', 'KATEGORIA ZAKTUALIZOWANA!'); 
        }else
            return redirect()->back()->with('danger', 'Problem z serwerem. REKORD NIE ZOSTAŁ ZAKTUALIZOWANY!');
    }

    protected function deleteExpenseNameCategory(Request $request){

        $howManyRecords = DB::table('expenses')->where('category_user_id', '=', $request->category_nameExpenseId, 'and','user_id', '=' ,Auth::id())->get();
        
        if($howManyRecords->count() >  0) 
        {
            return redirect()->back()->with('info', 'ABY USUNĄĆ  TĄ  KATEGORIĘ NAJPIERW EDYTUJ LUB USUŃ WSZYSTKIE REKORDY PRZYPISANE TEJ KATEGORII!');
        }
        else{
            if(DB::table('users_expenses')->where('id', '=', $request->category_nameExpenseId)->delete())
            {
                return redirect()->back()->with('success', 'KATEGORIA USUNIĘTA!'); 
            }
            else
                return redirect()->back()->with('danger', 'Problem z serwerem. REKORD NIE ZOSTAŁ USUNIĘTY!'); 
        }
    }

    protected function checkThePaymentMethodName($newName){

        $newCategory1= '';

        $deleteWitheChars = trim($newName); //Białe znaki

        $newCategory = mb_strtolower($deleteWitheChars, 'UTF-8'); //wszytkie litery małe
        $newCategory= mb_convert_case($newCategory, MB_CASE_TITLE, "UTF-8");
        
        $allPaymentMethodUserCategory = DB::table('users_payment_methods')->where('user_id', '=', Auth::id())->get();

        foreach($allPaymentMethodUserCategory as $userPaymentMethod){

            if($userPaymentMethod->payment_method == $newCategory)
                return $newCategory1; 
        }
        return $newCategory;
    }

    protected function createNewPaymentMethodNameCategory(Request $request){

        $newName = $request->newPaymentMethodCategory;

        $newCategory = SettingsController::checkThePaymentMethodName($newName);
        
        if($newCategory == ''){
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

        $newName = $request->newNameEditCategoryPaymentMethod;
        $newNameEditCategoryPaymentMethod = SettingsController::checkThePaymentMethodName($newName);
        if($newNameEditCategoryPaymentMethod == ''){
            return redirect()->back()->with('danger', 'TAKA KATEGORIA ISTNIEJE!');
        }

        if(DB::table('users_payment_methods')
        ->where('id', '=', $request->category_namePayOption )
        ->update([
            'payment_method' => $newNameEditCategoryPaymentMethod 
            ]))
        {
            return redirect()->back()->with('success', 'KATEGORIA ZAKTUALIZOWANA!'); 
        }
        else
            return redirect()->back()->with('danger', 'Problem z serwerem. REKORD NIE ZOSTAŁ ZAKTUALIZOWANY!'); 
   
    }

    protected function deletePaymentMethodNameCategory(Request $request){

        $howManyRecords = DB::table('expenses')->where('payment_method_id', '=', $request->category_namePaymentMethodId, 'and','user_id', '=' ,Auth::id())->get();
        
        if($howManyRecords->count() >  0) 
        {
            return redirect()->back()->with('info', 'ABY USUNĄĆ  TEN SPOSÓB PŁATNOŚCI NAJPIERW EDYTUJ LUB USUŃ WSZYSTKIE REKORDY Z TYM SPOSOBEM PŁATNOŚCI!');
        }
        else{
            if(DB::table('users_payment_methods')->where('id', '=', $request->category_namePaymentMethodId)->delete())
            {
                return redirect()->back()->with('success', 'KATEGORIA USUNIĘTA!'); 
            }
            else
                return redirect()->back()->with('danger', 'Problem z serwerem. REKORD NIE ZOSTAŁ USUNIĘTY!'); 
        }
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

