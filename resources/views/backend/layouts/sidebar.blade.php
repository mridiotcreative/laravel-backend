<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin') }}">
        <!-- <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Admin</div> -->

        <img src="{{ asset('backend/img/admin-logo.svg') }}" alt="">
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('admin') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>


    <!-- Divider -->
    {{-- <hr class="sidebar-divider"> --}}

    <!-- Heading -->
    {{-- <div class="sidebar-heading">
        Customer
    </div>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('customer.index') }}">
            <i class="fas fa-users"></i>
            <span>Customers</span></a>
    </li> --}}

    <!-- Divider -->
    {{-- <hr class="sidebar-divider"> --}}

    <!-- Heading -->
    {{-- <div class="sidebar-heading">
        Banner
    </div> --}}

    <!-- Nav Item - Pages Collapse Menu -->
    <!-- Nav Item - Charts -->
    {{-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true"
            aria-controls="collapseTwo">
            <i class="fas fa-image"></i>
            <span>Banners</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Banner Options:</h6>
                <a class="collapse-item" href="{{ route('banner.index') }}">Banners</a>
                <a class="collapse-item" href="{{ route('banner.create') }}">Add Banners</a>
            </div>
        </div>
    </li> --}}
    <!-- Divider -->
    <hr class="sidebar-divider">
    <!-- Heading -->
    <div class="sidebar-heading">
        Shop
    </div>

    <!-- Categories -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#categoryCollapse"
            aria-expanded="true" aria-controls="categoryCollapse">
            <i class="fas fa-sitemap"></i>
            <span>Category</span>
        </a>
        <div id="categoryCollapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Category Options:</h6>
                <a class="collapse-item" href="{{ route('category.index') }}">Category</a>
                <a class="collapse-item" href="{{ route('category.create') }}">Add Category</a>
            </div>
        </div>
    </li>
    {{-- Products --}}
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#productCollapse"
            aria-expanded="true" aria-controls="productCollapse">
            <i class="fas fa-cubes"></i>
            <span>Products</span>
        </a>
        <div id="productCollapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Product Options:</h6>
                <a class="collapse-item" href="{{ route('product.index') }}">Products</a>
                <a class="collapse-item" href="{{ route('product.create') }}">Add Product</a>
            </div>
        </div>
    </li>

    <!--Orders -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('order.index') }}">
            <i class="fas fa-hammer fa-chart-area"></i>
            <span>Orders</span>
        </a>
    </li>

    <!-- Reviews -->
    {{-- <li class="nav-item">
        <a class="nav-link" href="{{ route('review.index') }}">
            <i class="fas fa-comments"></i>
            <span>Reviews</span></a>
    </li> --}}


    <!-- Divider -->
    <hr class="sidebar-divider  d-none">

    <!-- Heading -->
    <div class="sidebar-heading d-none">
        Posts
    </div>

    <!-- Posts -->
    <li class="nav-item d-none">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#postCollapse" aria-expanded="true"
            aria-controls="postCollapse">
            <i class="fas fa-fw fa-folder"></i>
            <span>Posts</span>
        </a>
        <div id="postCollapse" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Post Options:</h6>
                <a class="collapse-item" href="{{ route('post.index') }}">Posts</a>
                <a class="collapse-item" href="{{ route('post.create') }}">Add Post</a>
            </div>
        </div>
    </li>

    <!-- Category -->
    <li class="nav-item d-none">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#postCategoryCollapse"
            aria-expanded="true" aria-controls="postCategoryCollapse">
            <i class="fas fa-sitemap fa-folder"></i>
            <span>Category</span>
        </a>
        <div id="postCategoryCollapse" class="collapse" aria-labelledby="headingPages"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Category Options:</h6>
                <a class="collapse-item" href="{{ route('post-category.index') }}">Category</a>
                <a class="collapse-item" href="{{ route('post-category.create') }}">Add Category</a>
            </div>
        </div>
    </li>

    <!-- Tags -->
    <li class="nav-item d-none">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#tagCollapse" aria-expanded="true"
            aria-controls="tagCollapse">
            <i class="fas fa-tags fa-folder"></i>
            <span>Tags</span>
        </a>
        <div id="tagCollapse" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Tag Options:</h6>
                <a class="collapse-item" href="{{ route('post-tag.index') }}">Tag</a>
                <a class="collapse-item" href="{{ route('post-tag.create') }}">Add Tag</a>
            </div>
        </div>
    </li>

    <!-- Comments -->
    {{-- <li class="nav-item">
        <a class="nav-link" href="{{ route('comment.index') }}">
            <i class="fas fa-comments fa-chart-area"></i>
            <span>Comments</span>
        </a>
    </li> --}}


    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block d-none">
    <!-- Heading -->
    <div class="sidebar-heading d-none">
        General Settings
    </div>
    {{-- <li class="nav-item">
        <a class="nav-link" href="{{ route('coupon.index') }}">
            <i class="fas fa-table"></i>
            <span>Coupon</span></a>
    </li> --}}
    <!-- Contact Us -->
    <li class="nav-item d-none">
        <a class="nav-link" href="{{ route('contact-us.index') }}">
            <i class="fas fa-cog"></i>
            <span>Contact Us</span></a>
    </li>
    <!-- Users -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('users.index') }}">
            <i class="fas fa-users"></i>
            <span>Users</span></a>
    </li>
    <!-- General settings -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('settings') }}">
            <i class="fas fa-cog"></i>
            <span>Settings</span></a>
    </li>


    <li class="nav-item d-none">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#cmsCollapse" aria-expanded="true"
            aria-controls="cmsCollapse">
            <i class="fas fa-table"></i>
            <span>CMS</span>
        </a>
        <div id="cmsCollapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">CMS Options:</h6>
                <a class="collapse-item" href="{{ route('cms.index') }}">CMS</a>
                <a class="collapse-item" href="{{ route('cms.create') }}">Add CMS</a>
            </div>
        </div>
    </li>

    <li class="nav-item d-none">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#pricemasterCollapse" aria-expanded="true"
            aria-controls="pricemasterCollapse">
            <i class="fas fa-coins"></i>
            <span>Price Master</span>
        </a>
        <div id="pricemasterCollapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Price Master Options:</h6>
                <a class="collapse-item" href="{{ route('pricemaster.index') }}">Price Master</a>
                <a class="collapse-item" href="{{ route('pricemaster.create') }}">Add Price Master</a>
            </div>
        </div>
    </li>

    <li class="nav-item d-none">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#OffermasterCollapse" aria-expanded="true"
            aria-controls="OffermasterCollapse">
            <i class="fas fa-gift"></i>
            <span>Offer Master</span>
        </a>
        <div id="OffermasterCollapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Offer Master Options:</h6>
                <a class="collapse-item" href="{{ route('offermaster.index') }}">Offer Master</a>
                <a class="collapse-item" href="{{ route('offermaster.create') }}">Add Offer Master</a>
            </div>
        </div>
    </li>

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
