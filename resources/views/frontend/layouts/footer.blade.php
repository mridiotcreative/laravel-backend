<!-- Start Footer Area -->
<footer class="footer">
    <!-- Footer Top -->
    <div class="footer-top section">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Single Widget -->
                    <div class="single-footer links">
                        <h4>Company</h4>
                        @php
                            $cmsDetails = (new \App\Helpers\AppHelper)->cmsDetails();
                        @endphp

                        <ul class="cmsList">
                        <?php
                            /*<li><a href="{{ route('about-us') }}">About Us</a></li>
                            <li><a href="{{ route('contact') }}">Contact Us</a></li>
                            <li><a href="#">Support</a></li>
                            <li><a href="#">Faq</a></li> */
                            foreach($cmsDetails as $cmskey => $cmsval):
                                echo '<li><a href='.route('cms.page.view', $cmsval->slug).'>'.$cmsval->title.'</a></li>';
                            endforeach;
                        ?>
                       </ul>

                    </div>
                    <!-- End Single Widget -->
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Single Widget -->
                    <div class="single-footer links">
                        <h4>Featured Categories</h4>
                        @php
                            $categoryDetails = (new \App\Helpers\AppHelper)->getfeaturedCategoryDetails();
                        @endphp
                        <ul>
                            @if(!empty($categoryDetails))
                                @foreach($categoryDetails as $catekey => $cateval)
                                    <li><a href="{{ route('product-grids') }}?category={{ $cateval->slug }}">{{ $cateval->title }}</a></li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                    <!-- End Single Widget -->
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Single Widget -->
                    <div class="single-footer social">
                        <h4>FOLLOW US</h4>
                    </div>
                    <div class="folow-all-icon">
                        <div class="facebook-ic">
                            <a href="https://www.facebook.com/" target="_blank">
                                <i class="fa fa-facebook-square" aria-hidden="true"></i>
                            </a>
                        </div>
                        <div class="insta-ic">
                            <a href="https://www.instagram.com/?hl=en" target="_blank">
                                <i class="fa fa-instagram" aria-hidden="true"></i>
                            </a>
                        </div>
                        <div class="twiter-ic">
                            <a href="https://twitter.com/" target="_blank">
                                <i class="fa fa-twitter" aria-hidden="true"></i>
                            </a>
                        </div>
                        <div class="youtube-ic">
                            <a href="https://www.youtube.com/" target="_blank">
                                <i class="fa fa-youtube-play" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>

                    <div class="download-app">
                        <h5>Download the App for Free</h5>
                    </div>
                    <div class="download-ic">
                        <div class="googleplay">
                            <a href="https://play.google.com/store/apps" target="_blank">
                                <img src="/frontend/img/android.svg" alt="">
                            </a>
                        </div>
                        <div class="app-store">
                            <a href="https://www.apple.com/in/app-store/" target="_blank">
                                <img src="/frontend/img/ios-1.svg" alt="">
                            </a>
                        </div>
                    </div>

                    <!-- End Single Widget -->
                </div>
                <div class="allrights">
                    <p>© 2022 VALUNOVA. All Rights Reserved</p>
                </div>
            </div>
        </div>
    </div>
    <!-- End Footer Top -->


    {{-- <div class="copyright">
        <div class="container">
            <div class="inner">
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <div class="left">
                            <p>Copyright © {{ date('Y') }} <a href="https://github.com/Prajwal100"
                                    target="_blank">Prajwal Rai</a> - All Rights Reserved.</p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="right">
                            <img src="{{ asset('backend/img/payments.png') }}" alt="#">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
</footer>
<!-- /End Footer Area -->

<!-- Jquery -->
<script src="{{ asset('frontend/js/jquery.min.js') }}"></script>
<script src="{{ asset('frontend/js/jquery-migrate-3.0.0.js') }}"></script>
<script src="{{ asset('frontend/js/jquery-ui.min.js') }}"></script>
<!-- Popper JS -->
<script src="{{ asset('frontend/js/popper.min.js') }}"></script>
<!-- Bootstrap JS -->
<script src="{{ asset('frontend/js/bootstrap.min.js') }}"></script>
<!-- Color JS -->
{{-- <script src="{{ asset('frontend/js/colors.js') }}"></script> --}}
<!-- Slicknav JS -->
<script src="{{ asset('frontend/js/slicknav.min.js') }}"></script>
<!-- Owl Carousel JS -->
<script src="{{ asset('frontend/js/owl-carousel.js') }}"></script>
<!-- Magnific Popup JS -->
<script src="{{ asset('frontend/js/magnific-popup.js') }}"></script>
<!-- Waypoints JS -->
<script src="{{ asset('frontend/js/waypoints.min.js') }}"></script>
<!-- Countdown JS -->
<script src="{{ asset('frontend/js/finalcountdown.min.js') }}"></script>
<!-- Nice Select JS -->
<script src="{{ asset('frontend/js/nicesellect.js') }}"></script>
<!-- Flex Slider JS -->
<script src="{{ asset('frontend/js/flex-slider.js') }}"></script>
<!-- ScrollUp JS -->
<script src="{{ asset('frontend/js/scrollup.js') }}"></script>
<!-- Onepage Nav JS -->
<script src="{{ asset('frontend/js/onepage-nav.min.js') }}"></script>
{{-- Isotope --}}
<script src="{{ asset('frontend/js/isotope/isotope.pkgd.min.js') }}"></script>
<!-- Easing JS -->
<script src="{{ asset('frontend/js/easing.js') }}"></script>

<!-- Active JS -->
<script src="{{ asset('frontend/js/active.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>


@stack('scripts')
<script>
     jQuery(document).ready(function() {
    jQuery(".dropdown-for-mobile").click(function() {
        if ($(window).width() < 767) {
            // alert("jhyg");
            jQuery(this).siblings(".single-widget").toggleClass("open");
            jQuery(this).children(".fa-angle-up").toggleClass("open");
        }
    });
    });
    setTimeout(function() {
        $('.alert').slideUp();
    }, 5000);
    $(function() {
        // ------------------------------------------------------- //
        // Multi Level dropdowns
        // ------------------------------------------------------ //
        $("ul.dropdown-menu [data-toggle='dropdown']").on("click", function(event) {
            event.preventDefault();
            event.stopPropagation();

            $(this).siblings().toggleClass("show");


            if (!$(this).next().hasClass('show')) {
                $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
            }
            $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
                $('.dropdown-submenu .show').removeClass("show");
            });

        });

        // Add To Cart
        $('.btn-add-to-cart').click(function() {
            var quantity = $('#quantity').val() ? $('#quantity').val() : 1;
            var slug = $(this).data('id');
            $.ajax({
                url: "{{ route('cart.add') }}",
                type: "POST",
                headers: {
                    "Accept": "application/json"
                },
                data: {
                    _token: "{{ csrf_token() }}",
                    quantity: quantity,
                    slug: slug
                },
                success: function(response) {
                    $("span.total-count").html(response.cart_total);
                    swal(response.message);
                },
                error: function(request, status, error) {
                    if (request.status == 401) {
                        //window.location.replace("{{ route('login.form') }}");
                    } else if (request.status == 500) {
                        swal(error);
                        console.log(request.responseText);
                    } else {
                        swal(request.responseText);
                    }
                }
            })
        });
    });
</script>
