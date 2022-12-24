@extends('frontend.email.layouts.master')

@section('title', 'Inactive Page')

@section('main-content')

    <h2>Order Placed.</h2>
    <p>Order No:- {{ $orderDetails['order_number'] }}</p>


@endsection
