@extends('layouts.app')

@section('title', 'Dołącz do nas!')

@section('navbar')
<nav class="navbar navbar-dark bg-nav-Wallet navbar-expand-lg"></nav>
@endsection

<!-- @if($errors->any())
    @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach 
@endif -->

@section('content')
<article class="walletspage">
    <div class="container">
        <header>
            <h1 class="font-weight-bold text-uppercase mb-2">{{ __('Rejsetracja') }}</h1>
        </header>
        <div class="row mb-4">
            <div class="col-sm-12 mx-auto my-auto">
                <div class="tile col-12 mx-auto my-auto">
                    <h2 class="h3 font-weight-bold my-3 text-uppercase">Wprowadź swoje dane:</h2>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="wrapperForm col-12 col-md-6 ml-2 my-3 mb-2">
                            <label>
                                <i class="icon-user">{{ __('Imię') }}</i>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Wprowadź imię" autofocus required>
                            </label>
                            <div>
                                @error('name')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror                      
                            </div>
                        </div>

                        <div class="wrapperForm col-12 col-md-6 ml-2 my-3 mb-2">
                            <label>
                                <i class="icon-mail-alt">{{ __('Adres email') }}</i>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Wprowadź email" required autocomplete="email">
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
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Wprowadź hasło" required autocomplete="new-password">
                            </label>
                            <div>
                                @error('password')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror                      
                            </div>
                        </div>
                    
                        <div class="wrapperForm col-12 col-md-6 ml-2 my-3 mb-2">
                            <label>
                                <i class="icon-key">{{ __('Powtórz Hasło') }}</i>
                                <input type="password" class="form-control" placeholder="Powtórz hasło" name="password_confirmation" required autocomplete="new-password">
                            </label>
                        </div>
                        
                    <label class="wrapper2 col-12 col-md-6 mx-auto mb-4">
                            <i class="icon-user-add"></i>
                            <input type="submit" id="logout" value="Zarejestruj się!">
                    </label>
                    
                    </form>
                </div>
            </div>
        </div>
    </div>
</article>
@endsection
