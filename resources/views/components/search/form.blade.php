@props([
'searchString' => null
])

{!! Form::open(['route' => 'search', 'method' => 'get', 'role' => 'search']) !!}
<div class="input-group">
    {!! Form::text('q', $searchString, ['class' => 'form-control', 'placeholder' => __('search/messages.input_help'), 'autofocus' => 'autofocus']) !!}
    <div class="input-group-append">
        {!! Form::button('<i class="fas fa-search"></i>', array('type' => 'submit', 'class' => 'btn btn-info')) !!}
    </div>
</div>
{!! Form::close() !!}
