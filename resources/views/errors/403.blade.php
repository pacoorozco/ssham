@extends('layouts.error')

{{-- Title --}}
@section('title', 'Error 403')

{{-- Content --}}
@section('content')
    <h1 class="error">403 Error</h1>
    <h4>Oops, you are not authorized to view this page. Forbidden!</h4>
    <p>We are sorry, but you do not have access to this page or resource.</p>
    <a href="{{ route('home') }}" class="btn btn-primary">Go to homepage</a>
@endsection

