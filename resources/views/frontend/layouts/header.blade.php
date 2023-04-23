<header class="header shop">
    <!-- Topbar -->
    <div class="topbar">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-12">
                    <!-- Top Left -->
                    @php
                        $getSettingData = (new \App\Helpers\AppHelper)->getSettingData();
                    @endphp
                    <div class="top-left">
                        <ul class="list-main">
                            <li><i class="ti-email"></i>
                                {{ $getSettingData['email'] }}
                            </li>
                        </ul>
                    </div>
                    <!--/ End Top Left -->
                </div>
                <div class="col-lg-6 col-md-12 col-12">
                    <!-- Top Right -->
                    <div class="right-content">
                        <ul class="list-main">
                            @auth('customer')
                                <li class="my-account">
                                    <a href="{{ route('user.profile.form') }}">
                                        <i class="ti-user"></i>
                                        <span> My Account</span>
                                    </a>
                                </li>
                                <li class="Logout">
                                    <a href="{{ route('user.logout') }}"> <img src="/frontend/img/logout.svg" alt="">
                                        <span>Logout</span> </a>
                                </li>
                            @else
                                <li>
                                    <a href="{{ route('login.form') }}"> <img src="/frontend/img/login-icon.svg" alt="">
                                    </a>
                                    <a href="{{ route('login.form') }}">Login /</a>
                                    <a href="{{ route('register.form') }}">Register</a>
                                </li>
                            @endauth
                        </ul>
                    </div>
                    <!-- End Top Right -->
                </div>
            </div>
        </div>
    </div>
    <!-- End Topbar -->
    <div class="middle-inner">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-2 col-12 logo-main-container">
                    <!-- Logo -->
                    <div class="logo">
                        <a href="{{ route('home') }}">
                            <img src="{{ $getSettingData['logo'] }}" alt="logo">
                        </a>
                    </div>
                    <!--/ End Logo -->
                    <!-- Search Form -->
                    <div class="search-top active">
                        <div class="top-search"><a href="#0"><i class="ti-search"></i></a></div>
                        <!-- Search Form -->
                        <div class="search-top">
                            <form class="search-form" method="GET" action="{{ route('product.search') }}">
                                <input type="text" placeholder="Search Products Here....." name="search"
                                    value="{{ request()->input('search') }}">
                                <button value="search" type="submit"><i class="ti-search"></i></button>
                            </form>
                        </div>
                        <!--/ End Search Form -->
                    </div>
                    <!--/ End Search Form -->
                    <div class="mobile-nav">
                        <a class="mobile-menu-bar">
                            <i class="fa fa-bars" aria-hidden="true"></i>
                        </a>

                    </div>
                </div>
                <div class="col-lg-8 col-md-7 col-12">
                    <div class="search-bar-top">
                        <div class="search-bar">
                            <form method="GET" action="{{ route('product.search') }}">
                                <input name="search" placeholder="Search Products Here....." type="search"
                                    value="{{ request()->input('search') }}">
                                <button class="btnn" type="submit"><i class="ti-search"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-12 cart-wrapper">
                    <div class="right-bar">
                        <!-- Search Form -->
                        <a href="/#" class="single-icon sinlge-bar shopping"> <img src="/frontend/img/setting.svg"
                                alt="">
                        </a>

                        <a href="{{ route('cart') }}" class="sinlge-bar shopping single-icon" href="">
                            <img src="/frontend/img/cart.svg" alt=""> <span
                                class="total-count">{{ Helper::cartCount() }}</span>
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header Inner -->
    <div class="header-inner">
        <div class="container">
            <div class="cat-nav-head">
                <div class="row">
                    <div class="col-lg-12 col-12">
                        <div class="menu-area">
                            <!-- Main Menu -->
                            <nav class="navbar navbar-expand-lg">
                                <div class="navbar-collapse">
                                    <div class="nav-inner">
                                        <ul class="nav main-menu menu navbar-nav">
                                            <div class="drower-close-btn">
                                                <a class="mobile-menu-bar">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                            <li>
                                                <a href="{{ route('home') }}">
                                                    Home
                                                </a>
                                            </li>
                                            <li class=""><a href="{{ route('product-grids') }}">Shop</a></li>
                                            <li class="">
                                                <a href="{{ route('about-us') }}">AboutUs</a>
                                            </li>
                                            <li class="">
                                                <a href="{{ route('contact') }}">Support</a>
                                            </li>
                                            @auth('customer')
                                                <li class="sticky-menu">
                                                    <a href="{{ route('user.logout') }}"> <img
                                                            src="/frontend/img/logout.svg" alt="">
                                                        <span>Logout</span> </a>
                                                </li>
                                            @else
                                                <li class="sticky-menu">
                                                    <a href="{{ route('login.form') }}">
                                                        <i class="ti-user"></i>
                                                        <span>Login</span>
                                                    </a>
                                                </li>
                                            @endauth
                                            <li class="sticky-menu">
                                                <div class="right-bar">
                                                    <a href="{{ route('cart') }}"
                                                        class="sinlge-bar shopping single-icon" href="">
                                                        <img src="/frontend/img/cart.svg" alt=""> <span
                                                            class="total-count">{{ Helper::cartCount() }}</span>
                                                    </a>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </nav>
                            <!--/ End Main Menu -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ End Header Inner -->
</header>
