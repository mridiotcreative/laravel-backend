@extends('frontend.layouts.master')

@section('title', 'Checkout page')

@section('main-content')

    <section class="section thankyou">
        <div class="container">
            <div class="thankyou-text">
                <h2>Tahnk you</h2>
                <p>for your order </p>
            </div>
            <div class="thank-you-txt">
                <p>your payment is complete</p>
            </div>
            <a href="{{ route('home') }}" class="continue-shoping">Continue Shopping</a>
        </div>
    </section>

@endsection
