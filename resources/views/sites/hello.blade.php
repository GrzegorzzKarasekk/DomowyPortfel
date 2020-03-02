@if(auth()->user())
  <script>
    window.location = "/home";
  </script>
@else 

@extends('layouts.app')

@section('title', 'Witaj użytkowniku!')

@section('navbar')
<nav class="navbar navbar-dark bg-nav-Wallet navbar-expand-lg"></nav>
@endsection

@section('content')
<article class="walletspage">
            <div class="container">
                <header>
                    <h1 class="font-weight-bold text-uppercase mb-2">Dziękujemy za rejestrację w serwisie! </h1>
                </header>
                <div class="row mb-4">
                    <div class="col-sm-12 mx-auto my-auto">
                        <div class="tile col-12 mx-auto my-auto">
                            <h2 class="h3 font-weight-bold my-3 text-uppercase">Możesz już przejść na swoje konto!
                            </h2>
                            <div class="wrapper1" onclick="window.location.href='/home'">
                                <i class="icon-login"></i>
                                <input type="button" id="login" value="Wejdź!">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
@endsection
@endif