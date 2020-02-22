@extends('layouts.app')


@section('title', 'Domowy Portfel')

@section('logo')
<div class="col-sm-12 logo mx-auto my-1 text-center">
        <a href="zarzadzaj-swoim-budzetem"><img src="jpg/portfelicon.jpg" class="img-fluid" alt="strona główna" /></a>
        <div class=" logotext d-inline-block">
            <span style="color:#A69886">Domowy</span>Portfel
        </div>
</div>
@endsection

@section('navbar')
<nav class="navbar navbar-dark bg-nav-Wallet navbar-expand-lg">

</nav>
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
                <div class="wrapper1 text-center" onclick="window.location.href='zaloguj-sie'">
                    <i class="icon-login"></i>
                    <input type="button" id="login" value="Zaloguj się!">
                </div>
            </div>
        </div>
        <div class="col-sm-6 mx-auto my-auto">
            <div class="tile">
                <h2 class="h3 font-weight-bold my-2 ">Chcę założyć konto</h2>
                <div class="wrapper2 text-center" onclick="window.location.href='zarejestruj-sie'">
                    <i class="icon-user-add"></i>
                    <input type="button" id="logout" value="Dołącz do nas!">

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
    <div class="rectangle">2020 &copy; Domowy portfel - Wszelkie prawa zastrzeżone <i class="icon-mail-alt"> </i>grzegorz.karasek.programista@gmail.com</div>
@endsection