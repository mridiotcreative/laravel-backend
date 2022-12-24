@extends('frontend.email.layouts.master')

@section('title', 'Reset Password')

@section('main-content')
    <a href="{{ $resetLink }}">Click Here</a>
@endsection
