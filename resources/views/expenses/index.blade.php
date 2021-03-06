@if(auth()->user())
@extends('layouts.app')

@section('title', 'Dodaj swój wydatek')

@push('scripts')
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="{{ asset('js/showExpenseStatus.js') }}" type="text/javascript"></script>
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

        <li class="nav-item mx-4"><a href="/logout">Wyloguj</a></li>

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
@endif

<div id="expenseInfo" ></div>

<article class="walletspage">
    <div class="container">
        <header>
            <h1 class="font-weight-bold text-uppercase mb-2">Dodaj nowy wydatek</h1>
        </header>
        <div class="quotation text-justify mb-4" style="font-size:20px">
            <q>Zasada nr 1 – nigdy nie trać pieniędzy. Zasada nr 2. – nigdy nie zapominaj o zasadzie nr 1.</q><span class=" d-inline-block font-weight-bold text-right blockquote-footer" style="font-size:20px">Warren Buffett</span>
        </div>
        <div class="row mb-4">
            <div class="col-sm-12 mx-auto my-auto">
                <div class="tile col-12 mx-auto my-auto">
                    <h2 class="h3 font-weight-bold my-3 text-uppercase">Wprowadź dane:</h2>

                    <form method="POST">
                        @csrf

                        <div class="wrapperForm col-12 col-md-6 mx-auto my-3 mb-2">
                            <label>
                               {{ __('Kwota:') }}</i>
                                <input type="number" id="amount" name="amount" class="form-control @error('amount') is-invalid @enderror" value="0.00" step="0.01" min="0.00" placeholder="Podaj kwotę przychodu" autofocus required>
                            </label>
                            <div>
                                @error('amount')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror                      
                            </div>
                        </div>

                        <div class="wrapperForm col-12 col-md-6 mx-auto my-3 mb-2">
                            <label>
                                {{ __('Data:') }}
                                <input type="date" name="transaction_date" class="form-control @error('transaction_date') is-invalid @enderror" value={{ $today_date }} min="2000-01-01" placeholder="Podaj kwotę przychodu" autofocus required>
                            </label>
                            <div>
                                @error('transaction_date')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror                      
                            </div>
                        </div>

                        <div class="wrapperForm col-12 col-md-6 mx-auto my-3 mb-2">
                            <fieldset>
                                <legend> Sposób płatności: </legend>
                                @foreach ($payoptions as $payoption)
                                    @if (Input::old('pay_option') == $payoption->id)
	                                    <div><label><input type="radio" name="pay_option" value="{{ $payoption->id }}" checked>{{ $payoption->payment_method }}</label></div>
                                    @else
                                        <div><label><input type="radio" name="pay_option" value="{{ $payoption->id }} ">{{ $payoption->payment_method }}</label></div>
                                    @endif
                                @endforeach
                            </fieldset>
                            <div>
                                @error('pay_option')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror                      
                            </div>
                        </div>

                        <div class="wrapperForm col-12 col-md-6 mx-auto my-3 mb-2">
                            <label for="category">{{ __('Kategoria transakcji:') }}</label>
                            <select id="category" name="category_name" style="width:100%;">
                            @foreach ($options as $option)
                                @if (Input::old('category_name') == $option->id)
                                    <option value="{{ $option->id }}" selected>{{ $option->category_name }}</option>
                                @else
                                    <option value="{{ $option->id }}">{{ $option->category_name }}</option>
                                @endif
                            @endforeach
                            </select>
                            <div>
                                @error('category_name')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror                      
                            </div>
                        </div>        

                       <div class="wrapperForm col-12 col-md-6 mx-auto my-3 mb-2">
                            <label for="description" class="relative">{{ __('Komentarz (opcjonalnie):') }}</label>
                            <br />
                            <textarea name="description" id="description" rows="4" cols="25" min="3" maxlength="100"></textarea>
                        </div>

                        <label class="wrapperAccept col-12 col-md-5 mx-2  my-3 mb-2 d-inline-block"><i class="icon-calendar-minus-o"></i><input type="submit" class="dodaj" value="Dodaj"></label>

                        <div class="wrapperCancel col-12 col-md-5 mx-2 my-3 mb-2 d-inline-block" onclick="window.location.href='/home'">
                            <i class="icon-calendar-times-o"></i>
                            <input type="button" class="anuluj" value="Anuluj">
                        </div>
                    </form>

                </div>
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