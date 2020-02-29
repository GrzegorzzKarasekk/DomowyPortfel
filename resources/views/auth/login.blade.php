@extends('layouts.app')

@section('title', 'Zaloguj Się')

@section('navbar')
<nav class="navbar navbar-dark bg-nav-Wallet navbar-expand-lg"></nav>
@endsection

@section('content')
<article class="walletspage">
    <div class="container">
        <header>
            <h1 class="font-weight-bold text-uppercase mb-2">Logowanie</h1>
        </header>
        <div class="row mb-4">
            <div class="col-sm-12 mx-auto my-auto">
                <div class="tile col-12 mx-auto my-auto">
                    <h2 class="h3 font-weight-bold my-2 text-uppercase">Podaj swoje dane:</h2>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="wrapperForm col-12 col-md-6 ml-2 mr-2 my-3 mb-2">
                            <label>
                                <i class="icon-mail-alt">{{ __('Adres email') }}</i>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Podaj swój email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            </label>
                            <div>
                                @error('email')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror                      
                            </div>
                        </div>

                        <div class="wrapperForm col-12 col-md-6 ml-2 my-3 mb-2">
                            <label>
                                <i class="icon-key">{{ __('Hasło') }}</i>
                                <input type="password" placeholder="Podaj swoje hasło" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">             
                            </label>
                            <div>
                                @error('password')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror                      
                            </div>
                        </div>

                        <label class="wrapper1 col-12 col-md-6 mx-auto mb-5">
                            <i class="icon-login"></i>
                            <input type="submit" id="login" value="Zaloguj się!">
                        </label>

                    </form>
                    
                    <span class="col-sm-12 mx-auto"><i>Nie posiadasz konta? - Dołącz do nas!</i></span>
                    <div class="wrapper2 col-12 col-md-6 mx-auto mb-4" onclick="window.location.href='/register'">
                        <i class="icon-user-add"></i>
                        <input type="button" id="logout" value="Dołącz do nas!">
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>
@endsection
