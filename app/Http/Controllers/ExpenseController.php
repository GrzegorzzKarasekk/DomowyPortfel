<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Expense;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
//use Symfony\Component\Console\Input\Input;

class ExpenseController extends Controller
{
    public function index()
    {
        $start = Carbon::now();   
        $today_date = $start->format('Y-m-d');
        $firstDay = $start->firstOfMonth()->format('Y-m-d');
        $lastDay = $start->lastOfMonth()->format('Y-m-d');

        $options = DB::table('users_expenses')->where('user_id', '=', Auth::id())->get(); 
        $payoptions = DB::table('users_payment_methods')->where('user_id', '=', Auth::id())->get(); 

        ExpenseController::createTableUserExpenses($firstDay, $lastDay);

        return view('expenses.index', compact('today_date', 'options', 'payoptions'));
    } 
    /**
     * Create a new income instance after a valid added income.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(Request $request)
    {
        $start = Carbon::now();   
        $today_date = $start->format('Y-m-d');
        $firstDay = $start->firstOfMonth()->format('Y-m-d');
        $lastDay = $start->lastOfMonth()->format('Y-m-d');

        $this->validate($request,[
            'amount' => ['required', 'numeric', 'min:0.01'],
            'transaction_date' => ['required', 'max:255','date_format:Y-m-d', 'before_or_equal:today_date'],
            'pay_option'=> ['required']
        ]);
        
        $userId = Auth::id();
        
        //Dodawanie do bazy
        Expense::create([
            'user_id' => $userId,
            'payment_method_id' => $request['pay_option'],
            'category_user_id' => $request['category_name'],
            'amount' => $request['amount'],
            'transaction_date' => $request['transaction_date'],
            'description' => $request['description'],            
        ]);

        if($request['transaction_date'] >= $firstDay && $request['transaction_date'] <= $lastDay){
            ExpenseController::createTableUserExpenses($firstDay, $lastDay);
        }

        return redirect()->back()->with('success', 'DODAŁEŚ NOWY WYDATEK!'); 
    }

    protected function editExpenseFromBalance(Request $request){
        
        $start = Carbon::now();   
        $today_date = $start->format('Y-m-d');
        $firstDay = $start->firstOfMonth()->format('Y-m-d');
        $lastDay = $start->lastOfMonth()->format('Y-m-d');

        $this->validate($request,[
            'transaction_date' => ['required', 'max:255','date_format:Y-m-d', 'before_or_equal:today_date'],
            'amount' => ['required', 'numeric', 'min:0.01'],            
            'category_name' => ['required'],
            'payment_method_id' => ['required'],
        ]);
        
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
            if($request['transaction_date'] >= $firstDay && $request['transaction_date'] <= $lastDay){
                ExpenseController::createTableUserExpenses($firstDay, $lastDay);
            }
            return redirect()->back()->with('success', 'WYDATEK ZAKTUALIZOWANY!'); 
        }
        else
            return redirect()->back()->with('danger', 'Problem z serwerem. REKORD NIE ZOSTAŁ ZAKTUALIZOWANY!'); 

    }

    protected function deleteExpenseFromBalance(Request $request){
        
        if(DB::table('expenses')->where('id', '=', $request->expenseId)->delete())
        {
            return redirect()->back()->with('success', 'WYDATEK USUNIĘTY!'); 
        }
        else
            return redirect()->back()->with('danger', 'Problem z serwerem. REKORD NIE ZOSTAŁ USUNIĘTY!'); 

    }

    private function createTableUserExpenses($firstDay, $lastDay)
    {
        
        DB::table('user_total_cost_of_expenses_in_this_mounth')->truncate();
        DB::table('user_total_cost_of_expenses_in_this_mounth')->increment('id');

        //Pobierz nazwy wszystkich wydatków użytkownika        
        $nameOfEveryUserExpenses = DB::table('users_expenses')->where('user_id', '=', Auth::id())->get();
        
        //Zapełnianie nowej tabeli wydatkami użytkownika       
        foreach ($nameOfEveryUserExpenses as $nameOfEveryUserExpense) {
            
            DB::table('user_total_cost_of_expenses_in_this_mounth')->insert(
                ['category_name_id' => $nameOfEveryUserExpense->id, 'category_name' => $nameOfEveryUserExpense->category_name,'amount' => 0,'limit' => $nameOfEveryUserExpense->limit]
            );
        }

        //Sumowanie kosztów dla poszczególnych kategorii
        $costsOfExpenses = DB::table('expenses')->where('user_id', '=', Auth::id())->whereBetween('transaction_date',[$firstDay, $lastDay])->get(); 
        foreach ($costsOfExpenses as $costsOfExpense) {

        //* Zapisanie obecnego kosztu do bazy
        //Pobranie kosztu z bazy Wydatków użytkownika
        $costsOfCurrentlyExpensesCategory = floatval($costsOfExpense->amount);
        
        //Pobranie łącznej kwoty dla danej kategorii, w danym okresie z tabeli "Na dany okres"
        $downloadCostsOfSelectCategory = DB::table('user_total_cost_of_expenses_in_this_mounth')->where('category_name_id', '=', $costsOfExpense->category_user_id)->first(); 
        
        //Koszt kategorii
        $costOfSelectCategory = floatval($downloadCostsOfSelectCategory->amount);
        //Koszt kategorii po dodaniu kosztu danego wydatku 
        $totalCost = $costOfSelectCategory + $costsOfCurrentlyExpensesCategory;
        
        //Aktualizacja tabeli z kosztami za okres
        DB::table('user_total_cost_of_expenses_in_this_mounth')
        ->where('category_name_id', '=', $costsOfExpense->category_user_id)
        ->update(['amount' => $totalCost]);
    
        }
        
    }
    
    protected function showStatusSelectedExpense(Request $request){    
    
        $dataOfSelectedExpenses = DB::table('user_total_cost_of_expenses_in_this_mounth')->where('category_name_id', '=', $request->categoryId)->get();

        $enteredAmount =  $request->amount;

        if($dataOfSelectedExpenses->count() == 1){
            foreach($dataOfSelectedExpenses as $dataOfSelectedExpense){
                $monthlyLimit = $dataOfSelectedExpense->limit;
                $actualyAmount = $dataOfSelectedExpense->amount;
            }    
        }

        if(isset($monthlyLimit)){
            $actualyWithEnteredAmount = $actualyAmount + $enteredAmount;
            $diff = $monthlyLimit - $actualyWithEnteredAmount;


            if($actualyWithEnteredAmount <= $monthlyLimit){
                         
            echo
            '<div id="expenseInfoBlock" class="mx-auto">
                <table id="expenseInfo_table_green">
                    <thead>
                        <tr class="expenseInfo_firstTr">
                            <td class="expenseInfo_tg-baqh">Limit</td>
                            <td class="expenseInfo_tg-baqh">Dotychczas Wydano</td>
                            <td class="expenseInfo_tg-baqh">Różnica</td>
                            <td class="expenseInfo_tg-baqh">Wydatki + wpisana kwota</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="expenseInfo_Tr">
                            <td class="expenseInfo_tg-baqh">'.number_format($monthlyLimit, 2).'</td>
                            <td class="expenseInfo_tg-baqh">'.number_format($actualyAmount, 2).'</td>
                            <td class="expenseInfo_tg-baqh">'.number_format($diff, 2).'</td>
                            <td class="expenseInfo_tg-baqh">'.number_format($actualyWithEnteredAmount, 2).'</td>
                        </tr>    
                    </tbody>
                </table>
            </div>';
            }
            else{

            echo 
            '<div id="expenseInfoBlock" class="mx-auto">
                <table id="expenseInfo_table_red">
                    <thead>
                        <tr class="expenseInfo_firstTr">
                            <td class="expenseInfo_tg-baqh">Limit</td>
                            <td class="expenseInfo_tg-baqh">Dotychczas Wydano</td>
                            <td class="expenseInfo_tg-baqh">Różnica</td>
                            <td class="expenseInfo_tg-baqh">Wydatki + wpisana kwota</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="expenseInfo_Tr">
                            <td class="expenseInfo_tg-baqh">'.number_format($monthlyLimit, 2).'</td>
                            <td class="expenseInfo_tg-baqh">'.number_format($actualyAmount, 2).'</td>
                            <td class="expenseInfo_tg-baqh">'.number_format($diff, 2).'</td>
                            <td class="expenseInfo_tg-baqh">'.number_format($actualyWithEnteredAmount, 2).'</td>
                        </tr>    
                    </tbody>
                </table>
            </div>';

            }
        }
    }

}