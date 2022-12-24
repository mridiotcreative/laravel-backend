<div class="shop-sidebar border-right my-account-sidebar">
    <div class="dropdown-for-mobile">
        <i class="fa fa-angle-up"></i>
    </div>
    <!-- Single Widget -->

    <div class="single-widget category">
        <ul class="account-list sidebar-list">
            <div class="my-account-main-img">
                <div class="account-img">
                    <img src="/frontend/img/my-account.svg" alt="">
                </div>
                <label for="my_account">My Account</label>

            </div>
            <li class="{{ request()->routeIs('user.profile.form') ? 'active' : '' }}"><a class="my-profile"
                    href="{{ route('user.profile.form') }}">My Profile</a>
            </li>
            {{-- {{ dd(request()) }} --}}
            <li class="{{ request()->routeIs('user.change.password.form') ? 'active' : '' }}"><a
                    href="{{ route('user.change.password.form') }}">Change Password</a></li>
            <li class="{{ request()->routeIs('user.address.list') ? 'active' : '' }}"><a
                    href="{{ route('user.address.list') }}">Manage Address</a></li>
        </ul>

        <ul class="account-list sidebar-list">
            <div class="my-order-main-img">
                <div class="account-img">
                    <img src="/frontend/img/order.svg" alt="">
                </div>
                <label for="my_order">Order</label>
            </div>
            <li class="{{ request()->routeIs('user.order.history') ? 'active' : '' }}"><a
                    href="{{ route('user.order.history') }}">Orders History</a></li>
            <li class="{{ request()->routeIs('user.order.past.items') ? 'active' : '' }}"><a
                    href="{{ route('user.order.past.items') }}">Order From Past Items</a></li>
        </ul>
        <ul class="account-list sidebar-list">
            <div class="my-incentive-main-img">
                <div class="account-img">
                    <img src="/frontend/img/incentive.svg" alt="">
                </div>
                <label for="my_order">Incentive</label>
            </div>
            <li class="{{ request()->routeIs('user.points') ? 'active' : '' }}"><a
                    href="{{ route('user.points') }}">My Points</a></li>
        </ul>
        <ul class="payments sidebar-list">
            <div class="logout-img">
                <img src="/frontend/img/acc-logout.svg" alt="">
            </div>
            <li class="{{ request()->routeIs('user.logout') ? 'active' : '' }}"><a
                    href="{{ route('user.logout') }}">Logout</a></li>
        </ul>
    </div>
</div>
