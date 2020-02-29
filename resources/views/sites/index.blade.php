@if(Route::has('login'))
  <script>
    window.location = "/home";
  </script>
@else 

@extends('layouts.app')

@section('title', 'Zarządzaj swoim budżetem')

@section('navbar')
<nav class="navbar navbar-dark bg-nav-Wallet navbar-expand-lg"></nav>
@endsection


@section('content')
<div class="container">
    <header>
        <h1 class="font-weight-bold text-uppercase text-center mb-2">Witaj w Twoim domowym portfelu!</h1>
        <ul class="mx-auto text-left">
            <li>Czy zastanawiałeś się kiedyś na co i w jaki sposób wydajesz pieniądze?</li>
            <li>Co generuje największe dochody, a co największe koszta? Chcesz trzymać rękę na swoim budżecie?</li>
            <li>Chcesz trzymać rękę na swoim budżecie?</li>
        </ul>
        <p class="text-justify">Jeżeli na któreś z podanych pytań odpowiedziałeś:
            <span class="font-weight-bold" style="color:#A69886;font-size:20px;text-shadow: -1px 0 #594A3E, 0 1px #594A3E, 1px 0 #594A3E, 0 -1px #594A3E;"><q> TAK </q></span>, zapraszamy do skorzystania z aplikacji internetowej, która pomoże Ci łatwo i w przestępny sposób wyświetlić i pomóc kontrolować Twoje zasoby finansowe.
        </p>
    </header>
    <div class="row mb-4">
        <div class="col-sm-6 mx-auto my-auto">
            <div class="tile">
                <h2 class="h3 font-weight-bold my-2 ">Posiadam już konto</h2>
                <div class="wrapper1 text-center" onclick="window.location.href='/login'">
                    <i class="icon-login"></i>
                    <input type="button" id="login" value="Zaloguj się!">
                </div>
            </div>
        </div>
        <div class="col-sm-6 mx-auto my-auto">
            <div class="tile">
                <h2 class="h3 font-weight-bold my-2 ">Chcę założyć konto</h2>
                <div class="wrapper2 text-center" onclick="window.location.href='/register'">
                    <i class="icon-user-add"></i>
                    <input type="button" id="logout" value="Dołącz do nas!">

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@endif
