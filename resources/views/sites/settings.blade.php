@if(auth()->user())
@extends('layouts.app')

@section('title', 'Zmień ustawienia')

@section('navbar')
<nav class="navbar navbar-dark bg-nav-Wallet navbar-expand-lg">

    <button class="navbar-toggler order-first" type="button" data-toggle="collapse" data-target="#mainmenu" aria-controls="mainmenu" aria-expanded="false" aria-label="Przełącznik nawigacji">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainmenu">

        <ul class="navbar-nav mx-auto">
            <li role="separator" class="divider"></li>

            <li class="nav-item mx-4"><a href="/home">Menu uzytkownika</a></li>

            <li role="separator" class="divider"></li>
            <div class="dropdown-divider"></div>

            <li class="nav-item mx-4"><a href="/income">Dodaj przychód</a></li>

            <li role="separator" class="divider"></li>
            <div class="dropdown-divider"></div>

            <li class="nav-item mx-4"><a href="/expense">Dodaj wydatek</a></li>

            <li role="separator" class="divider"></li>
            <div class="dropdown-divider"></div>

            <li class="nav-item mx-4"><a href="/balances">Przeglądaj bilans</a></li>

            <li role="separator" class="divider"></li>
            <div class="dropdown-divider"></div>

            <li class="nav-item mx-4"><a href="/settings">Ustawienia</a></li>

            <li role="separator" class="divider"></li>
            <div class="dropdown-divider"></div>

            <li class="nav-item mx-4"><a href="/logout">Wyloguj się</a></li>


            <li role="separator" class="divider"></li>

        </ul>

    </div>

</nav>
@endsection

@section('content')
@if (\Session::has('success'))
    <div class="alert alert-success">
        {!! \Session::get('success') !!}
    </div>
@elseif (\Session::has('danger'))    
<div class="alert alert-danger">
    {!! \Session::get('danger') !!}
</div>
@elseif($errors->has('name'))
<div class="alert alert-danger">
    COŚ POSZŁO NIE TAK, Sprawdź imię!
</div>
@elseif($errors->has('password'))
<div class="alert alert-danger">
    COŚ POSZŁO NIE TAK, Sprawdź hasło!
</div>
@endif

<!--////////////////////////////////////////////////////////////////////////////-->
<!-- Modal addIncome-->
<div class="modal" id="addIncomeModal" tabindex="-1" role="dialog" style="color:black;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Wpisz nową kategorię przychodu: </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action='/settings/newIncomeCategory'>
                    @csrf 
                    <label><input type="text" name="newIncomeCategory" placeholder="Nowa kategoria" required></label>

                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" name="kategoria_przychodu_add" value="Dodaj">
                        
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>

                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<!-- Modal editIncome-->
<div class="modal" id="editIncomeModal" tabindex="-1" role="dialog" style="color:black;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Wybierz kategorię i zmodyfikuj jej nazwę: </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action='/settings/editIncomeCategory'>
                    @csrf
                    <label for="category" style="font-weight: 700;">{{ __('Kategoria transakcji: ') }}</label>
                        <br />
                        <select id="category" name="category_name" style="width:100%;">
                            @foreach($userIncomes as $userIncome)
                                <option value="{{ $userIncome->id }}">{{ $userIncome->category_name }}</option>
                            @endforeach
                    </select> 
                <label><input type="text" name="newNameEditCategoryIncome" placeholder="Nowa nazwa" required></label>
            <div class="modal-footer">
                <input type="submit" class="btn btn-primary" name="kategoria_przychodu_edit" value="Zapisz">
                
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
            </div>
                </form>
        </div>
    </div>
    </div>
</div>
<!-- Modal deleteIncome-->
<div class="modal" id="deleteIncomeModal" tabindex="-1" role="dialog" style="color:black;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Wybierz kategorię przychodów do usunięcia: </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
            <form method="POST" enctype="multipart/form-data" action='/settings/deleteIncomeCategory'>
                @csrf
                <label for="category" style="font-weight: 700;">{{ __('Kategoria transakcji: ') }}</label>
                <br />
                <select id="category" name="category_nameIncomeId" style="width:100%;">
                    @foreach($userIncomes as $userIncome)
                        <option value="{{ $userIncome->id }}">{{ $userIncome->category_name }}</option>
                    @endforeach
                </select> 
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" name="kategoria_przychodu_delete" value="Usuń">

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>

                </div>
            </form>
            </div>

        </div>
    </div>
</div>
<!--////////////////////////////////////////////////////////////////////////////-->
<!-- Modal addExpense-->
<div class="modal" id="addExpenseModal" tabindex="-1" role="dialog" style="color:black;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Wpisz nową kategorię wydatku: </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action='/settings/newExpenseCategory'>
                    @csrf 
                    <label><input type="text" name="newExpenseCategory" placeholder="Nowa kategoria" required></label>

                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" name="kategoria_wydatku_add" value="Dodaj">
                        
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>

                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<!-- Modal editExpense-->
<div class="modal" id="editExpenseModal" tabindex="-1" role="dialog" style="color:black;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Wybierz kategorię i zmodyfikuj jej nazwę: </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action='/settings/editExpenseCategory'>
                    @csrf
                    <label for="category" style="font-weight: 700;">{{ __('Kategoria transakcji: ') }}</label>
                        <br />
                        <select id="category" name="category_nameE" style="width:100%;">
                            @foreach($userExpenses as $userExpense)
                                <option value="{{ $userExpense->id }}">{{ $userExpense->category_name }}</option>
                            @endforeach
                    </select> 
                <label><input type="text" name="newNameEditCategoryExpense" placeholder="Nowa nazwa" required></label>
            <div class="modal-footer">
                <input type="submit" class="btn btn-primary" name="kategoria_wydatku_edit" value="Zapisz">
                
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
            </div>
                </form>
        </div>
    </div>
    </div>
</div>
<!-- Modal deleteExpense-->
<div class="modal" id="deleteExpenseModal" tabindex="-1" role="dialog" style="color:black;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Wybierz kategorię wydatków do usunięcia: </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
            <form method="POST" enctype="multipart/form-data" action='/settings/deleteExpenseCategory'>
                @csrf
                <label for="category" style="font-weight: 700;">{{ __('Kategoria transakcji: ') }}</label>
                <br />
                <select id="category" name="category_nameExpenseId" style="width:100%;">
                    @foreach($userExpenses as $userExpense)
                        <option value="{{ $userExpense->id }}">{{ $userExpense->category_name }}</option>
                    @endforeach
                </select> 
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" name="kategoria_wydatku_delete" value="Usuń">

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>

                </div>
            </form>
            </div>

        </div>
    </div>
</div>
<!--////////////////////////////////////////////////////////////////////////////-->
<!-- Modal addPaymentMethod-->
<div class="modal" id="addPayModal" tabindex="-1" role="dialog" style="color:black;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Wpisz nową kategorię sposobu płatności: </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action='/settings/newPaymentMethodCategory'>
                    @csrf 
                    <label><input type="text" name="newPaymentMethodCategory" placeholder="Nowa kategoria" required></label>

                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" name="kategoria_sposobu_platnosci_add" value="Dodaj">
                        
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>

                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<!-- Modal editPaymentMethod-->
<div class="modal" id="editPayModal" tabindex="-1" role="dialog" style="color:black;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Wybierz kategorię i zmodyfikuj jej nazwę: </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action='/settings/editPaymentMethodCategory'>
                    @csrf
                    <label for="category" style="font-weight: 700;">{{ __('Kategoria sposobu płatności: ') }}</label>
                        <br />
                        <select id="category" name="category_namePayOption" style="width:100%;">
                            @foreach($userPaymentMethods as $userPaymentMethod)
                                <option value="{{ $userPaymentMethod->id }}">{{ $userPaymentMethod->payment_method }}</option>
                            @endforeach
                    </select> 
                <label><input type="text" name="newNameEditCategoryPaymentMethod" placeholder="Nowa nazwa" required></label>
            <div class="modal-footer">
                <input type="submit" class="btn btn-primary" name="kategoria_sposobu_platnosci_edit" value="Zapisz">
                
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
            </div>
                </form>
        </div>
    </div>
    </div>
</div>
<!-- Modal deletePaymentMethod-->
<div class="modal" id="deletePayModal" tabindex="-1" role="dialog" style="color:black;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Wybierz kategorię sposobu płatności do usunięcia: </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
            <form method="POST" enctype="multipart/form-data" action='/settings/deletePaymentMethodCategory'>
                @csrf
                <label for="category" style="font-weight: 700;">{{ __('Kategoria sposobu płatności: ') }}</label>
                <br />
                <select id="category" name="category_namePaymentMethodId" style="width:100%;">
                    @foreach($userPaymentMethods as $userPaymentMethod)
                        <option value="{{ $userPaymentMethod->id }}">{{ $userPaymentMethod->payment_method }}</option>
                    @endforeach
                </select> 
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" name="kategoria_sposobu_platnosci_delete" value="Usuń">

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>

                </div>
            </form>
            </div>

        </div>
    </div>
</div>

<!--////////////////////////////////////////////////////////////////////////////-->
<!-- Modal editUser-->

@if($userData->count() == 1)
    @foreach ($userData as $user)
    
<div class="modal" id="editUserModal" tabindex="-1" role="dialog" style="color:black;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Zmień swoje dane: </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action='/settings/editUser'>
                    @csrf 
                    
                    <label style="font-weight: 700;"><i class="icon-user"></i>Zmień imię<input type="text" class="form-control" name="name" value="{{ $user->name }}" placeholder="Zmień imię" required></label>
                    <br />
                    @error('name')
                            <span class="alert alert-danger">{{ $message }}</span>
                    @enderror 
                    <label style="font-weight: 700;"><i class="icon-key"></i>Nowe hasło</label>
                        <input type="password" class="form-control" name="password" placeholder="Zmień hasło" required>
                    <label style="font-weight: 700;"><i class="icon-key"></i>Powtórz nowe hasło</label>
                        <input type="password" class="form-control" placeholder="Powtórz nowe hasło" name="password_confirmation" required autocomplete="new-password">
                    @error('password')
                            <span class="alert alert-danger">{{ $message }}</span>
                    @enderror 
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" name ="edituser" value="Zapisz">

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>

                    </div>
                </form>
            </div>

        </div>
    </div>
</div>


<!--/////////////////////////////////////////////////////////////////////////////-->
<!-- Modal deleteUser-->
<div class="modal" id="deleteUserModal" tabindex="-1" role="dialog" style="color:black;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="color:#9C1311;text-align: center; font-weight: bold;">{{ $user->name }} czy na pewno chcesz usunąć swoje konto?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" data-toggle="modal" data-target="#deleteUserPermanently" class="btn btn-primary">Usuń
                </button>

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Anuluj</button>

            </div>
        </div>
    </div>
</div>

<!-- Modal deleteUserPermanently-->
<div class="modal" id="deleteUserPermanently" tabindex="-1" role="dialog" style="color:black;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="color:#9C1311; font-weight: bold;">{{ $user->name }}!!!</h5>
            </div>
            <div class="modal-body" style="background: #9C1311; text-align:center; !important;">
            USUNIĘCIE KONTA WIĄŻE SIĘ Z USUNIĘCIEM WSZYSTKICH TWOICH DANYCH!<br />
            CZY JESTEŚ PEWIEN TEJ OPERACJI?
            <form method="POST" enctype="multipart/form-data" action='/settings/deleteUser'>
                @csrf 
                <label style="font-weight: 700;">ABY USUNĄĆ KONTO WPISZ SWOJE HASŁO</label>
                <label style="font-weight: 700;"><i class="icon-key"></i><input type="password" name="passwordDelete" placeholder="Wpisz hasło" required></label>
            <div class="modal-footer">
                <input type="submit" style="backgroundcolor: red;" class="btn btn-primary" style="color: #9C1311;" name ="deleteuser" value="USUŃ">
            
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Anuluj</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
<article class="walletspage">
            <div class="container">
                <header>
                    <h1 class="font-weight-bold text-uppercase mb-2">Wybierz opcję do zmiany</h1>
                </header>

                <div class="row mb-4">
                    <div class="tileOptionsEdit col-12  mb-3">
                        <h2 class="h3 font-weight-bold my-2">{{ __('Dodaj: ') }}</h2>
                        <div class="addOptions addOptionIncome d-inline-block" data-toggle="modal" data-target="#addIncomeModal">{{ __('Kategorię Przychodu') }}</div>
                        <div class="addOptions addOptionExpense d-inline-block" data-toggle="modal" data-target="#addExpenseModal">{{ __('Kategorię Wydatku') }}</div>
                        <div class="addOptions addOptionPay d-inline-block" data-toggle="modal" data-target="#addPayModal">{{ __('Sposób Płatności') }}</div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="tileOptionsEdit col-12  mb-3">
                        <h2 class="h3 font-weight-bold my-2">{{ __('Edytuj: ') }}</h2>
                        <div class="editOptions editOptionIncome d-inline-block" data-toggle="modal" data-target="#editIncomeModal">{{ __('Kategorię Przychodu') }}</div>
                        <div class="editOptions editOptionExpense d-inline-block" data-toggle="modal" data-target="#editExpenseModal">{{ __('Kategorię Wydatku') }}</div>
                        <div class="editOptions editOptionPay d-inline-block" data-toggle="modal" data-target="#editPayModal">{{ __('Sposób Płatności') }}</div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="tileOptionsEdit col-12  mb-3">
                        <h2 class="h3 font-weight-bold my-2">{{ __('Usuń: ') }}</h2>
                        <div class="deleteOptions deleteOptionIncome d-inline-block" data-toggle="modal" data-target="#deleteIncomeModal">{{ __('Kategorię Przychodu') }}</div>
                        <div class="deleteOptions deleteOptionExpense d-inline-block" data-toggle="modal" data-target="#deleteExpenseModal">{{ __('Kategorię Wydatku') }}</div>
                        <div class="deleteOptions deleteOptionPay d-inline-block" data-toggle="modal" data-target="#deletePayModal">{{ __('Sposób Płatności') }}</div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="tileOptionsEdit col-12  mb-3">
                        <h2 class="h3 font-weight-bold my-2">{{ __('Zmień dane użytkownika: ') }}</h2>
                        <div class="editUser my-3" data-toggle="modal" data-target="#editUserModal">{{ __('Edytuj dane użytkownika') }}</div>
                        <div class="deleteUser my-3" data-toggle="modal" data-target="#deleteUserModal">{{ __('Usuń użytkownika') }}</div>
                    </div>
                </div>
            </div>
        </article>
@endsection
@else 
<script>
    window.location = "/login";
  </script>
@endif