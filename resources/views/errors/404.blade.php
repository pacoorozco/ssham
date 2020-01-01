@extends('layouts.error')

{{-- Title --}}
@section('title', 'Error 404')

@section('content')
    <h1 class="error">404 Error</h1>
    <h4>Oops! This page Could Not Be Found!</h4>
    <p>Sorry bit the page you are looking for does not exist, have been removed or name changed.</p>
    <a href="{{ route('home') }}" class="btn btn-primary">Go to homepage</a>
@endsection


