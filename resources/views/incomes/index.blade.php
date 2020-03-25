@if(auth()->user())
@extends('layouts.app')

@section('title', 'Dodaj swój przychód')

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
@endif

<article class="walletspage">
    <div class="container">
        <header>
            <h1 class="font-weight-bold text-uppercase mb-2">Dodaj nowy przychód</h1>
            <div class="quotation text-justify mb-4" style="font-size:20px">
                <q>Oszczędność jest to umiejętność unikania zbędnych wydatków</q><span class=" d-inline-block font-weight-bold text-right blockquote-footer" style="font-size:20px">Seneka Młodszy</span>
            </div>
        </header>
        
        <div class="row mb-4">
            <div class="col-sm-12 mx-auto my-auto">
                <div class="tile col-12 mx-auto my-auto">
                    <h2 class="h3 font-weight-bold my-3 text-uppercase">Wprowadź dane:</h2>

                    <form method="POST">
                        @csrf
                        
                        <div class="wrapperForm col-12 col-md-6 mx-auto my-3 mb-2">
                            <label>
                               {{ __('Kwota:') }}</i>
                                <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" value="0.00" step="0.01" min="0.00" placeholder="Podaj kwotę przychodu" autofocus required>
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


                        <label class="wrapperAccept col-12 col-md-5 mx-2  my-3 mb-2 d-inline-block"><i class="icon-calendar-plus-o"></i><input type="submit" class="dodaj" value="Dodaj"></label>

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