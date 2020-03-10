@extends('layouts.app')

@section('title', 'Witaj')

@section('navbar')
<nav class="navbar navbar-dark bg-nav-Wallet navbar-expand-lg">

<button class="navbar-toggler order-first" type="button" data-toggle="collapse" data-target="#mainmenu" aria-controls="mainmenu" aria-expanded="false" aria-label="Przełącznik nawigacji">
    <span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="mainmenu">

    <ul class="navbar-nav mx-auto">

        <li role="separator" class="divider"></li>
        
        <li class="nav-item mx-4"><a href="{{ url('/income') }}">Dodaj przychód</a></li>

        <li role="separator" class="divider"></li>
        <div class="dropdown-divider"></div>

        <li class="nav-item mx-4"><a href="{{ url('/expense') }}">Dodaj wydatek</a></li>

        <li role="separator" class="divider"></li>
        <div class="dropdown-divider"></div>

        <li class="nav-item mx-4"><a href="{{ url('/balances') }}">Przeglądaj bilans</a></li>

        <li role="separator" class="divider"></li>
        <div class="dropdown-divider"></div>

        <li class="nav-item mx-4"><a href="{{ url('/settings') }}">Ustawienia</a></li>

        <li role="separator" class="divider"></li>
        <div class="dropdown-divider"></div>
        
        <li class="nav-item mx-4"><a href="{{ url('/logout') }}">Wyloguj się</a></li>
  
    </ul>

</div>
</nav>
@endsection
@section('content')
<article class="walletspage">
    <div class="container">
       
        <header>
            <h1 class='font-weight-bold text-uppercase mb-2'>Witaj {{ Auth::user()->name }} :D</h1>
            <div class="quotation text-justify mb-4" style="font-size:20px">
                <q>Pieniądzom trzeba rozkazywać, a nie służyć </q><span class=" d-inline-block font-weight-bold text-right blockquote-footer" style="font-size:20px">Seneka</span>
            </div>
        </header>

        <div class="row mb-2">
            <div class="col-sm-6 mx-auto my-auto" onclick="window.location.href='{{ url('/income') }}'">
                <div class="tileMenu">
                    <div id="menuTile1">
                        <i class="icon-calendar-plus-o display-inline-block"></i>
                        <input type="button" class="menuButton1 display-inline-block" value="Dodaj przychód">
                    </div>
                </div>
            </div>

            <div class="col-sm-6 mx-auto my-auto" onclick="window.location.href='{{ url('/expense') }}'">
                <div class="tileMenu">
                    <div id="menuTile2">
                        <i class="icon-calendar-minus-o display-inline-block"></i>
                        <input type="button" class="menuButton2" value="Dodaj wydatek">
                    </div>
                </div>
            </div>
        </div>

        <div class="row my-2">
            <div class="col-sm-6 mx-auto my-auto" onclick="window.location.href='{{ url('/balances') }}'">
                <div class="tileMenu">
                    <div id="menuTile3">
                        <i class="icon-calc display-inline-block"></i>
                        <input type="button" class="menuButton3 display-inline-block" value="Przegladaj bilans">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col-sm-6 mx-auto my-auto" onclick="window.location.href='{{ url('/settings') }}'">
                <div class="tileMenu">
                    <div id="menuTile4">
                        <i class="icon-cog-alt display-inline-block"></i>
                        <input type="button" class="menuButton4 display-inline-block" value="Zmień ustawienia">
                    </div>
                </div>
            </div>

            <div class="col-sm-6 mx-auto my-auto" onclick="window.location.href='{{ url('/logout') }}'">
                <div class="tileMenu">
                    <div id="menuTile5">
                        <i class="icon-logout display-inline-block"></i>
                        <input type="button" class="menuButton5 display-inline-block" value=" Wyloguj się">
                    </div>
                </div>
            </div>
        </div>             
    </div>
</article>
@endsection



















