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

    protected function unregular(Request $request)
    {

        $dataczas = new DateTime();
        $today_date = $dataczas->format('Y-m-d');

        // echo $request['transaction_date'];
        // echo "</br>";
        // echo $today_date;

        $this->validate($request,[
            'unregularDay1' => ['required', 'max:255','date_format:Y-m-d', 'before_or_equal:today_date'],
            'unregularDay2' => ['required', 'max:255','date_format:Y-m-d', 'before_or_equal:today_date'],
        ]);
        
        // $userId = Auth::id();
        // // $userId = Auth::user()->id;

        // //Dodawanie do bazy
        // if(Income::create([
        //     'user_id' => $userId,
        //     'category_user_id' => $request['category_name'],
        //     'amount' => $request['amount'],
        //     'transaction_date' => $request['transaction_date'],
        //     'description' => $request['description'],            
        // ]))
        // {
        //     return redirect()->back()->with('success', 'DODAŁEŚ NOWY PRZYCHÓD!'); 
        // }
        // else
        // return redirect()->back()->with('danger', 'NIE UDAŁO SIĘ DODAĆ PRZUCHODU :('); 
        //redirect()->back()->with('success', 'your message here');   
    }

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
        $chart = BalancesController::createChart();
             
        $costOfIncomes = BalancesController::createChart($rangeIncomes);

        $costOfExpenses = BalancesController::createChart($rangeExpenses);        

        // foreach($rangeIncomes as $rangeIncome){
        //     echo $rangeIncome->id;
        //     echo '<||>';
        //     echo $rangeIncome->user_id;
        //     echo '<||>';
        //     echo $rangeIncome->category_user_id;
        //     echo '<||>';
        //     echo $rangeIncome->amount;
        //     echo '<||>';
        //     echo $rangeIncome->transaction_date;
        //     echo '<||>';
        //     echo $rangeIncome->description;
        //     echo '</br>';
        // };
        
        // foreach($rangeExpenses as $rangeExpense){
        //     echo $rangeExpense->id;
        //     echo '<||>';
        //     echo $rangeExpense->user_id;
        //     echo '<||>';
        //     echo $rangeExpense->category_user_id;
        //     echo '<||>';
        //     echo $rangeExpense->payment_method_id;
        //     echo '<||>';
        //     echo $rangeExpense->amount;
        //     echo '<||>';
        //     echo $rangeExpense->transaction_date;
        //     echo '<||>';
        //     echo $rangeExpense->description;
        //     echo '</br>';
        // };

        return view('balances.index', compact('chart','today_date', 'firstDay', 'lastDay', 'rangeIncomes', 'costOfIncomes', 'nameOfIncomes'));//, 'rangeExpenses', 'costOfExpenses', 'nameOfPayOptions'));
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
 
    public function createChart()
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
                        
            $chart = new UserExpensesChart;
            $chart->title('Wydatki użytkownika w danym okresie');
            $chart->dataset('Łączny wydatek', 'pie', $userDataExpenses->pluck('amount'))->options(['color' => $colors]);
            // $chart->dataset('Łączny wydatek w zł', 'pie', $userDataExpenses->pluck('amount'));
            $chart->labels($userDataExpenses->pluck('category_name'));
            $chart->doughnut($size = 50);//pączek
            //$chart->tooltip(['valueSuffix'=> "zł"]); 
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