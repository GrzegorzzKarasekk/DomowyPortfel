@if(auth()->user())

@extends('layouts.app')

@section('title', 'Przeglądaj bilans z bieżącego miesiąca')

@push('scripts')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
@endpush

@section('navbar')
<nav class="navbar navbar-dark bg-nav-Wallet navbar-expand-lg">

<button class="navbar-toggler order-first" type="button" data-toggle="collapse" data-target="#mainmenu" aria-controls="mainmenu" aria-expanded="false" aria-label="Przełącznik nawigacji">
    <span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="mainmenu">
    <ul class="navbar-nav mx-auto">
        <li role="separator" class="divider"></li>

        <li class="nav-item mx-4"><a href="/home">Menu użytkownika</a></li>

        <li role="separator" class="divider"></li>


        <li class="nav-item mx-4"><a href="/income">Dodaj przychód</a></li>

        <li role="separator" class="divider"></li>


        <li class="nav-item mx-4"><a href="/expense">Dodaj wydatek</a></li>

        <li role="separator" class="divider"></li>


        <li class="nav-item mx-4"><a href="/settings">Ustawienia</a></li>

        <li role="separator" class="divider"></li>


        <li class="nav-item mx-4"><a href="/logout">Wyloguj</a></li>

        <li role="separator" class="divider"></li>

    </ul>
</div>

<ul class="navbar nav-item dropdown" style="list-style-type: none;">
    <li>
        <a class="nav-link dropdown-toggle" href="/balances" data-toggle="dropdown" role="button" aria-expanded="false" id="submenu" aria-haspopup="true"><span><i class="icon-calendar"></i></span>Wybierz okres dat</a>

        <div class="dropdown-menu">
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="/balances"> Bieżący miesiąc</a>
            <div class="dropdown-divider"></div>

            <a class="dropdown-item" href="/balances/lastMonth"> Poprzedni miesiąc</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="/balances/thisYear"> Bieżący rok</a>
            <div class="dropdown-divider"></div>
            <a class="btn btn-primary dropdown-item" data-toggle="modal" data-target="#unregularModal" href="/balances/unregular"> Niestandardowy</a>
            <div class="dropdown-divider"></div>
        </div>

    </li>
</ul>
</nav>
@endsection

@section('content')

<!-- Modal unregular date-->
<div class="modal" id="unregularModal" tabindex="-1" role="dialog" style="color:black;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Wybierz zakres dat</h5>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action='/balances/unregular'>
                    @csrf
                    <label for="dateF"> Od:</label> 
                    <label class="d-inline-block"><input type="date" id="dateF" name="unregularDay1" min="2000-01-01" value={{ $today_date }} required></label>
                    <label for="dateL"> Do:</label> 
                    <label class="d-inline-block"><input type="date" id="dateL" name="unregularDay2" min="2000-01-01" value={{ $today_date }} required></label>
                    @error('unregularDay1')
                            <span class="alert alert-danger">{{ $message }}</span>
                    @enderror 
                    @error('unregularDay2')
                            <span class="alert alert-danger">{{ $message }}</span>
                    @enderror 
            <div class="modal-footer">
                <input type="submit" class="btn btn-primary" value="Akceptuj">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
            </div>
                </form>
            </div>
        </div>
    </div>
</div>
        
@if (\Session::has('success'))
    <div class="alert alert-success">
        {!! \Session::get('success') !!}
    </div>
    @elseif (\Session::has('danger'))    
    <div class="alert alert-danger">
        {!! \Session::get('danger') !!}
    </div>
    @elseif($errors->has('unregularDay1') || $errors->has('unregularDay2'))
    <div class="alert alert-danger">
        COŚ POSZŁO NIE TAK, Sprawdź datę!
    </div>
    @elseif($errors->has('transaction_date'))
    <div class="alert alert-danger">
        COŚ POSZŁO NIE TAK, Sprawdź datę!
    </div>
    @elseif($errors->has('amount'))
    <div class="alert alert-danger">
        COŚ POSZŁO NIE TAK, Sprawdź kwotę!
    </div>
@endif

<article class="walletspage">
    <div class="container">
        <header class="dateBalance">
            <h1 class="font-weight-bold text-uppercase mb-2">Bilans za bieżący miesiąc</h1>
            <div class="textIn" style="color: #594A3E; font-size: 20px; font-weight: bold;">
                Od: {{ $firstDay }} do: {{ $lastDay }}
            </div>
        </header>
        <div class="quotation text-justify mb-4" style="font-size:20px">
            <q>Zrobić budżet to wskazać swoim pieniądzom, dokąd mają iść, zamiast się zastanawiać, gdzie się rozeszły</q><span class=" d-inline-block font-weight-bold text-right blockquote-footer" style="font-size:20px">John C. Maxwell</span>
        </div>
        <div class="row mb-4">
        <div class="tileIncomes col-12 mb-3">
            <h2 class="h3 font-weight-bold my-2">Przychody</h2>
            <table class="tg">
                <thead>
                    <tr class="firstTr">
                        <td class="tg-baqh">#</td>
                        <td class="tg-baqh">Data</td>
                        <td class="tg-baqh">Kwota</td>
                        <td class="tg-baqh">Kategoria</td>
                        <td class="tg-baqh">Komentarz</td>
                        <td class="tg-baqh">Edytuj</td>
                        <td class="tg-baqh">Kasuj</td>
                    </tr>
                </thead>
                <tbody>
                @if( !($rangeIncomes->isEmpty()))
                    @foreach($rangeIncomes as $rangeIncome)
                    
                    <tr class="incomeTr">
                        <td class="tg-baqh">{{ $rangeIncome->id ?? '' }}</td>
                        <td class="tg-baqh">{{ $rangeIncome->transaction_date ?? '' }}</td>
                        <td class="tg-baqh">{{ number_format($rangeIncome->amount, 2) ?? '' }}</td>
                        @foreach($nameOfIncomes as $nameOfIncome)
                            @if($rangeIncome->category_user_id == $nameOfIncome->id )
                                <td class="tg-baqh">{{ $nameOfIncome->category_name ?? '' }}</td>
                            @endif
                        @endforeach                            
                        <td class="tg-baqh">{{ $rangeIncome->description ?? '' }}</td>
                        <td class="tg-baqh edit" data-toggle="modal" data-target="#editIncomeModal{{ $rangeIncome->id ?? '' }}"><i class="icon-edit"></i></td>
                        <td class="tg-baqh delete" data-toggle="modal" data-target="#dataIncomeToTrasch{{ $rangeIncome->id ?? '' }}"><i class="icon-trash"></i></td>

                        <!-- Modal Income-->
                        <div class="modal" id="editIncomeModal{{ $rangeIncome->id ?? '' }}" tabindex="-1" role="dialog" style="color:black;">
                            <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edytuj przychód nr: {{ $rangeIncome->id ?? '' }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body" style="text-align:left !important;">
                                <form method="POST"action='/balances/editIncome'>
                                    @csrf
                                    <input type="hidden" name="incomeId" value="{{ $rangeIncome->id ?? '' }}">
                                    
                                    <label for="date" style="font-weight: 700;">{{ __('Data: ') }}</label>
                                    <label><input type="date" id="date" name="transaction_date" min="2000-01-01" value="{{ $rangeIncome->transaction_date ?? '' }}"></label>
                                    <br />
                                    @error('transaction_date')
                                        <span class="alert alert-danger">{{ $message }}</span>
                                    @enderror 
                                    
                                    <label for="income" style="font-weight: 700;">{{ __('Kwota:') }}</label>
                                    <label><input type="number" id="income" name="amount" placeholder="Podaj kwotę przychodu" step="0.01" min="0.00" value="{{ $rangeIncome->amount ?? '' }}"></label>
                                    <br />
                                    @error('amount')
                                        <span class="alert alert-danger">{{ $message }}</span>
                                    @enderror 
                                    
                                    <label for="category" style="font-weight: 700;">{{ __('Kategoria transakcji:') }}</label>
                                    <select id="category" name="category_name" style="width:100%;">
                                    @foreach($nameOfIncomes as $nameOfIncome)
                                        @if ($nameOfIncome->id == $rangeIncome->category_user_id)
                                            <option value="{{ $nameOfIncome->id }}" selected>{{ $nameOfIncome->category_name }}</option>
                                        @else
                                            <option value="{{ $nameOfIncome->id }}">{{ $nameOfIncome->category_name }}</option>
                                        @endif
                                    @endforeach
                                    </select>
                                                                        
                                    <label for="description" class="relative" value="{{ $rangeIncome->description ?? '' }}">{{ __('Komentarz (opcjonalnie):') }}</label>
                                    <br />
                                    <textarea name="description" id="description" rows="4" cols="25" min="3" maxlength="100"></textarea>

                                    <div class="modal-footer">
                                        <input type="submit" class="btn btn-primary" value="Zapisz">

                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>

                                    </div>
                                </form>
                                </div>
                            </div>
                            </div>
                        </div>

                        <!-- Modal delete Income-->
                        <div class="modal" id="dataIncomeToTrasch{{ $rangeIncome->id ?? '' }}" tabindex="-1" role="dialog" style="color:black;">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Czy na pewno usunąć dane przychodu nr {{ $rangeIncome->id ?? '' }} ?</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-footer">
                                        <form action='/balances/deleteIncome' method="POST">
                                        @csrf
                                        <input type="hidden" name="incomeId" value="{{ $rangeIncome->id ?? '' }}">
                                    
                                        <input type="submit" class="btn btn-primary" value="Usuń">
                                        
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Anuluj</button>
                                    </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </tr>
                    @endforeach
                @elseif($rangeIncomes->count() == 0)
                    BRAK PRZYCHODÓW NA TEN MIESIĄC
                @else
                    <span style="color:red;">BRAK POŁĄCZENIA Z SERWEREM, PRZEPRASZAMY SPRÓBUJ PÓŹNIEJ</span>
                @endif
                </tbody>
            </table>
        </div>
    
        <div class="tileExpenses col-12  mb-3">
            <h2 class="h3 font-weight-bold my-2">Wydatki</h2>
            <table class="tg">
                <thead>
                    <tr class="firstTr">
                        <td class="tg-baqh">#</td>
                        <td class="tg-baqh">Data</td>
                        <td class="tg-baqh">Kwota</td>
                        <td class="tg-baqh">Sposób płatności</td>
                        <td class="tg-baqh">Kategoria</td>
                        <td class="tg-baqh">Komentarz</td>
                        <td class="tg-baqh">Edytuj</td>
                        <td class="tg-baqh">Kasuj</td>
                    </tr>
                </thead>
                <tbody>

                    @if( !($rangeExpenses->isEmpty()))
                        @foreach($rangeExpenses as $rangeExpense)
                            <tr class="expenseTr">
                                <td class="tg-baqh">{{ $rangeExpense->id ?? '' }}</td>
                                <td class="tg-baqh">{{ $rangeExpense->transaction_date ?? '' }}</td>
                                <td class="tg-baqh">{{ number_format($rangeExpense->amount, 2) ?? '' }}</td>
                                    @foreach($nameOfPayOptions as $nameOfPayOption)
                                        @if($rangeExpense->payment_method_id == $nameOfPayOption->id )
                                        <td class="tg-baqh">{{ $nameOfPayOption->payment_method ?? '' }}</td>
                                        @endif
                                    @endforeach
                                    @foreach($nameOfExpenses as $nameOfExpense)
                                        @if($rangeExpense->category_user_id == $nameOfExpense->id )
                                        <td class="tg-baqh">{{ $nameOfExpense->category_name ?? '' }}</td>
                                        @endif
                                    @endforeach
                                <td class="tg-baqh">{{ $rangeExpense->description ?? '' }}</td>
                                <td class="tg-baqh edit" data-toggle="modal" data-target="#editExpenseModal{{ $rangeExpense->id ?? '' }}"><i class="icon-edit"></i></td>
                                <td class="tg-baqh delete" data-toggle="modal" data-target="#dataExpenseToTrasch{{ $rangeExpense->id ?? '' }}"><i class="icon-trash"></i></td>

                                <!-- Modal Expense-->
                                <div class="modal" id="editExpenseModal{{ $rangeExpense->id ?? '' }}" tabindex="-1" role="dialog" style="color:black;">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edytuj wydatek nr: {{ $rangeExpense->id ?? '' }}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body" style="text-align:left !important;">
                                                <form method="POST" action='/balances/editExpense'>
                                                    @csrf

                                                    <input type="hidden" name="expenseId" value="{{ $rangeExpense->id ?? '' }}">

                                                    <label for="dateE" style="font-weight: 700;">{{ __('Data: ') }}</label>
                                                    <label><input type="date" id="dateE" name="transaction_date" min="2000-01-01" value="{{ $rangeExpense->transaction_date ?? '' }}"></label>
                                                    @error('transaction_date')
                                                        <span class="alert alert-danger">{{ $message }}</span>
                                                    @enderror
                                                    <br />

                                                    <label for="expense" style="font-weight: 700;">{{ __('Kwota: ') }}</label>
                                                    <label><input type="number" id="expense" name="amount" placeholder="Podaj kwotę wydatku" step="0.01" min="0.00" value="{{ $rangeExpense->amount ?? '' }}"></label>
                                                    @error('amount')
                                                        <span class="alert alert-danger">{{ $message }}</span>
                                                    @enderror
                                                    <br />

                                                    <label for="payment_method_id" style="font-weight: 700;">{{ __('Sposób płatności transakcji: ') }}</label>
                                                    <br />
                                                    <fieldset>
                                                    @if( $nameOfPayOptions->count() > 0)
                                                        @foreach($nameOfPayOptions as $nameOfPayOption)
                                                            @if($nameOfPayOption->id == $rangeExpense->payment_method_id)
                                                                <div><label><input type='radio' value="{{ $nameOfPayOption->id ?? '' }}" name='payment_method_id' checked>{{ $nameOfPayOption->payment_method ?? '' }}</label></div>
                                                            @else
                                                                <div><label><input type='radio' value="{{ $nameOfPayOption->id ?? '' }}" name='payment_method_id'>{{ $nameOfPayOption->payment_method ?? '' }}</label></div>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    </fieldset>

                                                    <label for="categoryE" style="font-weight: 700;">{{ __('Kategoria transakcji: ') }}</label>
                                                    <br />
                                                    <select id="categoryE" name="category_name" style="width:100%;">
                                                    @foreach($nameOfExpenses as $nameOfExpense)
                                                        @if ($nameOfExpense->id == $rangeExpense->category_user_id)
                                                            <option value="{{ $nameOfExpense->id }}" selected>{{ $nameOfExpense->category_name }}</option>
                                                        @else
                                                            <option value="{{ $nameOfExpense->id }}">{{ $nameOfExpense->category_name }}</option>
                                                        @endif
                                                    @endforeach
                                                    </select>

                                                    <label for="descriptionE" class="relative" value="{{ $rangeExpense->description ?? '' }}">{{ __('Komentarz (opcjonalnie):') }}</label>
                                                    <br />
                                                    <textarea name="description" id="descriptionE" rows="4" cols="25" min="3" maxlength="100"></textarea>

                                                    <div class="modal-footer">
                                                        <input type="submit" class="btn btn-primary" value="Zapisz">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal delete Expense-->
                                <div class="modal" id="dataExpenseToTrasch{{ $rangeExpense->id ?? '' }}" tabindex="-1" role="dialog" style="color:black;">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Czy na pewno usunąć dane wydatku nr {{ $rangeExpense->id ?? '' }} ?</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-footer">
                                                <form action='/balances/deleteExpense' method="POST">
                                                @csrf
                                                <input type="hidden" name="expenseId" value="{{ $rangeExpense->id ?? '' }}">
                                            
                                                <input type="submit" class="btn btn-primary" value="Usuń">
                                                
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Anuluj</button>
                                            </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </tr>
                        @endforeach
                    @elseif($rangeExpenses->count() == 0)
                        BRAK WYDATKÓW NA TEN MIESIĄC
                    @else
                        <span style="color:red;">BRAK POŁĄCZENIA Z SERWEREM, PRZEPRASZAMY SPRÓBUJ PÓŹNIEJ</span>';
                    @endif
                </tbody>
            </table>
        </div>
        </div>

        <div class="row mb-4">
            <div class="col-sm-12 mx-auto my-auto" style="padding: 0;">
                    <div class="tile mb-3">
                        <h2 class="h3 font-weight-bold my-3 mx-auto ">Bilans na dany okres: {{ number_format($totalCost, 2) }}</h2>
                        @if($totalCost > 0)
                            <div class="text-uppercase" style="font-weight: 700; color:#3A6623;">Gratulacje. Świetnie zarządzasz finansami!</div>                                
                        @elseif($totalCost == 0)
                            <div class="text-uppercase" style="font-weight: 700; color:#595959;">Jest dobrze, choć może być lepiej :)</div> 
                        @else
                            <div class="text-uppercase" style="font-weight: 700; color:#b22222;">Uważaj, wpadasz w długi!</div>                                
                        @endif                          
                    </div>
            </div>
        </div>
        @if($rangeExpenses->count() > 0)
            <div style="width: 75%" class='chartStyle'>
                {!! $chart->container() !!}
            </div>
            {!! $chart->script() !!}
        @endif
    </div>
</article>

@endsection
@else
<script>
    window.location = "/login";
</script>
@endif