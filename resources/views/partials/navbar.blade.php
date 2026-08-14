<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

<div class="container">

<a class="navbar-brand" href="/">AGM Ecommerce</a>

<button class="navbar-toggler"
type="button"
data-bs-toggle="collapse"
data-bs-target="#navbarNav">

<span class="navbar-toggler-icon"></span>

</button>

<div class="collapse navbar-collapse"
id="navbarNav">

<ul class="navbar-nav me-auto">

<li class="nav-item">

<a class="nav-link" href="/">Home</a>

</li>

<li class="nav-item">

<a class="nav-link" href="{{ route('shop') }}">Shop</a>

</li>

<li class="nav-item">

<a class="nav-link" href="#">Categories</a>

</li>

</ul>

<ul class="navbar-nav">

@guest

<li class="nav-item">

<a class="nav-link"
href="{{ route('login') }}">

Login

</a>

</li>

<li class="nav-item">

<a class="nav-link"
href="{{ route('register') }}">

Register

</a>

</li>

@endguest

@auth

<li class="nav-item">

    <a class="nav-link position-relative"
       href="{{ route('cart.index') }}">

        <i class="fa-solid fa-cart-shopping"></i>

        Cart

        @auth

            @if($cartCount > 0)

                <span id="cartCount"
                    class="badge bg-danger rounded-pill">

                    {{ $cartCount }}

                </span>
            @endif

        @endauth

    </a>

</li>
<li class="nav-item">

<span class="nav-link">

{{ auth()->user()->name }}

</span>

</li>

<li class="nav-item">

<form action="{{ route('logout') }}"
method="POST">

@csrf

<button class="btn btn-danger btn-sm mt-1">

Logout

</button>

</form>

</li>

@endauth

</ul>

</div>

</div>

</nav>