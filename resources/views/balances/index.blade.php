@if(auth()->user())

@extends('layouts.app')

@section('title', 'Dodaj swój wydatek')

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

        <li class="nav-item mx-4"><a href="menu-uzytkownika">Menu użytkownika</a></li>

        <li role="separator" class="divider"></li>


        <li class="nav-item mx-4"><a href="dodaj-przychod">Dodaj przychód</a></li>

        <li role="separator" class="divider"></li>


        <li class="nav-item mx-4"><a href="dodaj-wydatek">Dodaj wydatek</a></li>

        <li role="separator" class="divider"></li>


        <li class="nav-item mx-4"><a href="zmien-ustawienia">Ustawienia</a></li>

        <li role="separator" class="divider"></li>


        <li class="nav-item mx-4"><a href="wyloguj">Wyloguj</a></li>

        <li role="separator" class="divider"></li>

    </ul>
</div>

<ul class="navbar nav-item dropdown" style="list-style-type: none;">
    <li>
        <a class="nav-link dropdown-toggle" href="przegladaj-bilans" data-toggle="dropdown" role="button" aria-expanded="false" id="submenu" aria-haspopup="true"><span><i class="icon-calendar"></i></span>Wybierz okres dat</a>

        <div class="dropdown-menu">
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href=" przegladaj-bilans"> Bieżący miesiąc</a>
            <div class="dropdown-divider"></div>

            <a class="dropdown-item" href="poprzedni-miesiac"> Poprzedni miesiąc</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="ten-rok"> Bieżący rok</a>
            <div class="dropdown-divider"></div>
            <a class="btn btn-primary dropdown-item" data-toggle="modal" data-target="#unregularModal" href="balance/unregular"> Niestandardowy</a>
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
                <form method="post">
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
        
<!-- Botstrap o poinformowaniu edycji dochodu -->
<div class="modal" tabindex="-1" id="infoModal" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="color:black; font-size:15px; text-align: center;">
                <h5 class="modal-title">{{ $dochodZaktualizowany ?? '' }}</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Zamknij</button>
            </div>
        </div>
    </div>
</div>

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
                            <td class="tg-baqh">{{ $rangeIncome->category_user_id ?? '' }}</td>
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
                                        <form action="editIncomeFromBilance.php" method="post">
                                            <input type="hidden" name="incomeId" value="{{ $rangeIncome->id ?? '' }}">
                                            
                                            <label for="date" style="font-weight: 700;">{{ __('Data: ') }}</label>
                                            <label><input type="date" id="date" name="transaction_date" min="2000-01-01" value="{{ $rangeIncome->transaction_date ?? '' }}"></label>
                                            <br />
                                            @error('transaction_date')
                                                <span class="alert alert-danger">{{ $message }}</span>
                                            @enderror 
                                            
                                            <label for="income" style="font-weight: 700;">{{ __('Kwota:') }}</label>
                                            <label><input type="number" id="income" name="amount" placeholder="Podaj kwotę przychodu" step="0.01" min="0.00" value="{{ $rangeIncome->amount ?? '' }}"></label><br />
                                            <br />
                                            
                                            <label for="category" style="font-weight: 700;">{{ __('Kategoria transakcji:') }}</label>
                                            <select id="category" name="category_name" style="width:100%;">
                                            @foreach($nameOfIncomes as $nameOfIncome)
                                                @if ($rangeIncome->category_user_id == $nameOfIncome->id)
                                                    <option value="{{ $rangeIncome->id }}" selected>{{ $nameOfIncome->category_name }}</option>
                                                @else
                                                    <option value="{{ $rangeIncome->id }}">{{ $nameOfIncome->category_name }}</option>
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

                        </tr>
                        @endforeach
                    @elseif($rangeIncomes->count() == 0)
                        { __('BRAK PRZYCHODÓW NA TEN MIESIĄC')}
                    @else
                        <span style="color:red;">{ __('BRAK POŁĄCZENIA Z SERWEREM, PRZEPRASZAMY SPRÓBUJ PÓŹNIEJ')}</span>';
                    @endif
                    </tbody>
                </table>
            </div>












    
<div style="width: 50%" class='chartStyle'>
    {!! $chart->container() !!}
</div>
    {!! $chart->script() !!}
</div>
</article>

@endsection
   
@if(isset($dochodZaktualizowany))
{        
    <script type='text/javascript'>
    $(document).ready(function(){
    $('#infoModal').modal('show');
    });
    </script>
    unset($dochodZaktualizowany);
}
@endif


@else
<script>
    window.location = "/login";
</script>
@endif