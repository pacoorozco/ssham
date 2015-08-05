@extends('layouts.error')

{{-- Title --}}
@section('title', 'Error 403')

{{-- Content --}}
@section('content')
<!-- start: 403 -->
<div class="col-sm-12 page-error">
    <div class="error-number teal">
        403
    </div>
    <div class="error-details col-sm-6 col-sm-offset-3">
        <h3>Oops, you are not authorized to view this page. Forbidden!</h3>
        <p>
            <a href="{!! route('home') !!}" class="btn btn-teal btn-return">
                Return home
            </a>
        </p>
    </div>
</div>
<!-- end: 403 -->
@endsection
