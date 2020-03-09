<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Expense;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Charts\UserExpensesChart;

class BalancesController extends Controller
{

    public function index()
    {
        $start = Carbon::now();   
        $today_date = $start->format('Y-m-d');
        $firstDay = $start->firstOfMonth()->format('Y-m-d');
        $lastDay = $start->lastOfMonth()->format('Y-m-d');
     
        //Dochody
        $rangeIncomes = DB::table('incomes')->where('user_id', '=', Auth::id())->whereBetween('transaction_date',[$firstDay, $lastDay])->get(); 
        $nameOfIncomes = DB::table('users_incomes')->where('user_id', '=', Auth::id())->get(); 
        //Wydatki
        $rangeExpenses = DB::table('expenses')->where('user_id', '=', Auth::id())->whereBetween('transaction_date',[$firstDay, $lastDay])->get(); 
        $nameOfExpenses = DB::table('users_expenses')->where('user_id', '=', Auth::id())->get(); 
        $nameOfPayOptions = DB::table('users_payment_methods')->where('user_id', '=', Auth::id())->get(); 
        //Stworzenie tabeli wydatków
        BalancesController::createTableUserExpenses($firstDay, $lastDay);
        //Stworzenie wykresu na podstawie tabeli
        $chart = BalancesController::createChart($firstDay, $lastDay);
             
        $costOfIncomes = BalancesController::sumOfIncomes($rangeIncomes);

        $costOfExpenses = BalancesController::sumOfExpenses($rangeExpenses);        

        
        $totalCost = $costOfIncomes - $costOfExpenses;


        return view('balances.index', compact('chart','today_date', 'firstDay', 'lastDay', 'rangeIncomes', 'nameOfIncomes', 'rangeExpenses', 'nameOfPayOptions', 'nameOfExpenses', 'totalCost'));
    }    

    public function lastMonth()
    {
        $start = Carbon::now();   
        $today_date = $start->format('Y-m-d');

        $firstDay = new Carbon('first day of last month');
        $firstDay = $firstDay->format('Y-m-d');
        $lastDay =  new Carbon('last day of last month');
        $lastDay = $lastDay->format('Y-m-d');
        //Dochody
        $rangeIncomes = DB::table('incomes')->where('user_id', '=', Auth::id())->whereBetween('transaction_date',[$firstDay, $lastDay])->get(); 
        $nameOfIncomes = DB::table('users_incomes')->where('user_id', '=', Auth::id())->get(); 
        //Wydatki
        $rangeExpenses = DB::table('expenses')->where('user_id', '=', Auth::id())->whereBetween('transaction_date',[$firstDay, $lastDay])->get(); 
        $nameOfExpenses = DB::table('users_expenses')->where('user_id', '=', Auth::id())->get(); 
        $nameOfPayOptions = DB::table('users_payment_methods')->where('user_id', '=', Auth::id())->get(); 
        //Stworzenie tabeli wydatków
        BalancesController::createTableUserExpenses($firstDay, $lastDay);
        //Stworzenie wykresu na podstawie tabeli
        $chart = BalancesController::createChart($firstDay, $lastDay);
             
        $costOfIncomes = BalancesController::sumOfIncomes($rangeIncomes);

        $costOfExpenses = BalancesController::sumOfExpenses($rangeExpenses);        

        
        $totalCost = $costOfIncomes - $costOfExpenses;


        return view('balances.lastMonth', compact('chart','today_date', 'firstDay', 'lastDay', 'rangeIncomes', 'nameOfIncomes', 'rangeExpenses', 'nameOfPayOptions', 'nameOfExpenses', 'totalCost'));
    }    

    public function thisYear()
    {
        $start = Carbon::now();   
        $today_date = $start->format('Y-m-d');

        $firstDay = new Carbon('first day of January this year');
        $firstDay = $firstDay->format('Y-m-d');
               
        $lastDay = $today_date;
        //Dochody
        $rangeIncomes = DB::table('incomes')->where('user_id', '=', Auth::id())->whereBetween('transaction_date',[$firstDay, $lastDay])->get(); 
        $nameOfIncomes = DB::table('users_incomes')->where('user_id', '=', Auth::id())->get(); 
        //Wydatki
        $rangeExpenses = DB::table('expenses')->where('user_id', '=', Auth::id())->whereBetween('transaction_date',[$firstDay, $lastDay])->get(); 
        $nameOfExpenses = DB::table('users_expenses')->where('user_id', '=', Auth::id())->get(); 
        $nameOfPayOptions = DB::table('users_payment_methods')->where('user_id', '=', Auth::id())->get(); 
        //Stworzenie tabeli wydatków
        BalancesController::createTableUserExpenses($firstDay, $lastDay);
        //Stworzenie wykresu na podstawie tabeli
        $chart = BalancesController::createChart($firstDay, $lastDay);
             
        $costOfIncomes = BalancesController::sumOfIncomes($rangeIncomes);

        $costOfExpenses = BalancesController::sumOfExpenses($rangeExpenses);        

        
        $totalCost = $costOfIncomes - $costOfExpenses;


        return view('balances.thisYear', compact('chart','today_date', 'firstDay', 'lastDay', 'rangeIncomes', 'nameOfIncomes', 'rangeExpenses', 'nameOfPayOptions', 'nameOfExpenses', 'totalCost'));
    }    


    public function unregular(Request $request)
    {

        $start = Carbon::now(); 
        $today_date = $start->format('Y-m-d');


        $this->validate($request,[
            'unregularDay1' => ['required', 'max:255','date_format:Y-m-d', 'before_or_equal:today_date'],
            'unregularDay2' => ['required', 'max:255','date_format:Y-m-d', 'before_or_equal:today_date'],
        ]);
        
        $firstDay = $request->unregularDay1;
        $lastDay = $request->unregularDay2;

        $rangeIncomes = DB::table('incomes')->where('user_id', '=', Auth::id())->whereBetween('transaction_date',[$firstDay, $lastDay])->get(); 
        $nameOfIncomes = DB::table('users_incomes')->where('user_id', '=', Auth::id())->get(); 
        //Wydatki
        $rangeExpenses = DB::table('expenses')->where('user_id', '=', Auth::id())->whereBetween('transaction_date',[$firstDay, $lastDay])->get(); 
        $nameOfExpenses = DB::table('users_expenses')->where('user_id', '=', Auth::id())->get(); 
        $nameOfPayOptions = DB::table('users_payment_methods')->where('user_id', '=', Auth::id())->get(); 
        //Stworzenie tabeli wydatków
        BalancesController::createTableUserExpenses($firstDay, $lastDay);
        //Stworzenie wykresu na podstawie tabeli
        $chart = BalancesController::createChart($firstDay, $lastDay);
             
        $costOfIncomes = BalancesController::sumOfIncomes($rangeIncomes);

        $costOfExpenses = BalancesController::sumOfExpenses($rangeExpenses);        

        
        $totalCost = $costOfIncomes - $costOfExpenses;


        return view('balances.unregular', compact('chart','today_date', 'firstDay', 'lastDay', 'rangeIncomes', 'nameOfIncomes', 'rangeExpenses', 'nameOfPayOptions', 'nameOfExpenses', 'totalCost'));

    }

    public function createTableUserExpenses($firstDay, $lastDay)
    {
        
        DB::table('user_total_cost_of_expenses_in_time')->truncate();
        DB::table('user_total_cost_of_expenses_in_time')->increment('id');

        //Pobierz nazwy wszystkich wydatków użytkownika        
        $nameOfEveryUserExpenses = DB::table('users_expenses')->where('user_id', '=', Auth::id())->get();
        
        //Zapełnianie nowej tabeli wydatkami użytkownika       
        foreach ($nameOfEveryUserExpenses as $nameOfEveryUserExpense) {
            
            DB::table('user_total_cost_of_expenses_in_time')->insert(
                ['category_name_id' => $nameOfEveryUserExpense->id, 'category_name' => $nameOfEveryUserExpense->category_name,'amount' => 0]
            );
        }

        //Sumowanie kosztów dla poszczególnych kategorii
        $costsOfExpenses = DB::table('expenses')->where('user_id', '=', Auth::id())->whereBetween('transaction_date',[$firstDay, $lastDay])->get(); 
        foreach ($costsOfExpenses as $costsOfExpense) {

        //* Zapisanie obecnego kosztu do bazy
        //Pobranie kosztu z bazy Wydatków użytkownika
        $costsOfCurrentlyExpensesCategory = floatval($costsOfExpense->amount);
        // echo "\n".$costsOfCurrentlyExpensesCategory;
        
        //Pobranie łącznej kwoty dla danej kategorii, w danym okresie z tabeli "Na dany okres"
        $downloadCostsOfSelectCategory = DB::table('user_total_cost_of_expenses_in_time')->where('category_name_id', '=', $costsOfExpense->category_user_id)->first(); 
        // echo $downloadCostsOfSelectCategory;
        // echo "PRZERWA";
                    
        // //Koszt kategorii
        $costOfSelectCategory = floatval($downloadCostsOfSelectCategory->amount);
        //echo "\n".$costOfSelectCategory;
        //Koszt kategorii po dodaniu kosztu danego wydatku 
        $totalCost = $costOfSelectCategory + $costsOfCurrentlyExpensesCategory;
        //echo $totalCost;
        
        //Aktualizacja tabeli z kosztami za okres
        DB::table('user_total_cost_of_expenses_in_time')
        ->where('category_name_id', '=', $costsOfExpense->category_user_id)
        ->update(['amount' => $totalCost]);
    
        }
        
    }
 
    public function createChart($firstDay, $lastDay)
    {
        
        //Pobranie danych do odczytu wykresu
        $userDataExpenses = DB::table('user_total_cost_of_expenses_in_time')->where('amount', '>', 0)->get();
        
        $chart = NULL;

        if ($userDataExpenses == NULL)
        {
            return $chart;
        }
        else
        {
            $howMany = $userDataExpenses->count();
            $colors = BalancesController::getRandomColor($howMany);
             
            $title = 'Wydatki użytkownika w okresie od:'.$firstDay.' do:'.$lastDay;

            $chart = new UserExpensesChart;
            //$chart->title($title);
            $chart->title($title, $font_size = '150%', $color = '#A69886', $bold = true);
            $chart->dataset('Łączny wydatek', 'pie', $userDataExpenses->pluck('amount'))->options(['color' => $colors]);
            // $chart->dataset('Łączny wydatek w zł', 'pie', $userDataExpenses->pluck('amount'));
            $chart->labels($userDataExpenses->pluck('category_name'));
            $chart->doughnut($size = 50);//pączek
            $chart->options([
                'tooltip' => [
                    'valueSuffix' => ' zł '
                ],
                'plotOptions' => [
                    'pie'=> [
                        'allowPointSelect' => 'false',
                        'cursor' => 'pointer',
                        'dataLabels' =>[
                            'enabled' => 'true',
                            'format' => '<b>{point.name}</b>: {point.percentage:.1f} %',
                            'connectorColor' => 'silver'
                        ]
                    ]
                ],
                'chart'=> [
                    'backgroundColor' => 'transparent',
                ]
            ]);
            $chart->options([
                
            ]);

            return $chart;
        }
    }

    function getRandomColor($howMany) {
    
        $colors = collect([]);
        
        for($i= 0; $i < $howMany; $i++){
            
            $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
            $color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];

            $colors->push($color)->count(); 
        }

        return $colors;
    }

    public function sumOfIncomes($rangeIncomes){

        $totalCost = 0.00;

        foreach($rangeIncomes as $rangeIncome){

            $totalCost = $totalCost + $rangeIncome->amount;
        }

        return $totalCost;
    }

    public function sumOfExpenses($rangeExpenses){

        $totalCost = 0.00;

        foreach($rangeExpenses as $rangeExpense){

            $totalCost = $totalCost + $rangeExpense->amount;
        }

        return $totalCost;
    }




}