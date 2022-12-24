@extends('frontend.layouts.master')

@section('title', __('page_title.blogs'))

@section('main-content')
    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{ route('home') }}">Home<i class="fa fa-angle-right"></i></a></li>
                            <li class="active"><a href="javascript:void(0);">Blogs</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->

    <!-- Start Blog Single -->
    <section class="blog-single shop-blog grid section">
        <div class="container">
            <div class="blog-page-main">
                <div class="row">
                    <div class="col-lg-6 blog-banner-top">
                        <div class="blog-top-banner-left">
                            <a href="{{ route('blog.detail', $post->slug) }}">
                                <img src="{{ $post->photo[0] }}" alt="{{ $post->title }}" width="100%">
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6 blog-content-top">
                        <div class="blog-top-banner-right">
                            <div class="banner-date">
                                <p>{{ $post->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="banner-head">
                                <a href="{{ route('blog.detail', $post->slug) }}" class="title">
                                    <h3>{{ $post->title }}</h3>
                                </a>
                            </div>
                            <div class="blog-card-para">
                                {!! $post->summary !!}
                            </div>
                            <div class="blog-card-read-more">
                                <a href="{{ route('blog.detail', $post->slug) }}" class="more-btn">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($posts as $post)
                    <div class="col-lg-4 col-md-6 col-12">
                        <!-- Start Single Blog  -->
                        <div class="shop-single-blog">
                            <a href="{{ route('blog.detail', $post->slug) }}">
                                <img src="{{ $post->photo[0] }}" alt="{{ $post->title }}">
                            </a>
                            <div class="content">
                                <div class="blog-text">
                                    <a href="{{ route('blog.detail', $post->slug) }}"
                                        class="title">{{ $post->title }}</a>
                                    {!! $post->summary !!}
                                    <a href="{{ route('blog.detail', $post->slug) }}" class="more-btn">Read
                                        More</a>
                                </div>
                            </div>
                        </div>
                        <!-- End Single Blog  -->
                    </div>
                @endforeach
            </div>
            {{-- <div class="row">

                <div class="blog-pagination-main">
                    <nav aria-label="Page navigation example">
                        {!! $posts->links() !!}
                    </nav>
                </div>
            </div> --}}
        </div>
    </section>
    <!--/ End Blog Single -->
@endsection
@push('styles')
    <style>
        .pagination {
            display: inline-flex;
        }

    </style>
@endpush
