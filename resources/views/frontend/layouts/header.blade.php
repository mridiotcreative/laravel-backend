<header class="header shop">
    <!-- Topbar -->
    <div class="topbar">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-12">
                    <!-- Top Left -->
                    <div class="top-left">
                        <ul class="list-main">
                            <li><i class="ti-email"></i>
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
                            <img src="#" alt="logo">
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
                                                <!-- <div class="home-icon"> -->
                                                    <a href="{{ route('home') }}">
                                                        <!-- <svg xmlns="http://www.w3.org/2000/svg" width="20" height="22"
                                                            viewBox="0 0 20 22" fill="none">
                                                            <path
                                                                d="M1 8L10 1L19 8V19C19 19.5304 18.7893 20.0391 18.4142 20.4142C18.0391 20.7893 17.5304 21 17 21H3C2.46957 21 1.96086 20.7893 1.58579 20.4142C1.21071 20.0391 1 19.5304 1 19V8Z"
                                                                stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                            <path d="M7 21V11H13V21" stroke="white" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg> -->
                                                        Home
                                                    </a>
                                                <!-- </div> -->
                                            </li>
                                            <li class=""><a href="/#">Top Deals</a></li>
                                            <li class=""><a href="/#">Incentive</a></li>
                                            <li class=""><a href="{{ route('blogs') }}">Health
                                                    Article</a></li>
                                            <li class="">
                                                <a href="{{ route('about-us') }}">AboutUs</a>
                                            </li>
                                            <li class="">
                                                <a href="{{ route('contact') }}">Support</a>
                                            </li>
                                            @auth('customer')
                                                {{-- <li class="my-account">
                                                    <a href="{{ route('user.profile.form') }}">
                                                        <i class="ti-user"></i>
                                                        <span> My Account</span>
                                                    </a>
                                                </li> --}}
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
