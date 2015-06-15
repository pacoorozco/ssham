@if (count($errors) > 0)
<div class="alert alert-block alert-danger fade in">
    <a class="close" data-dismiss="alert" aria-hidden="true">&times;</a>
    <h4 class="alert-heading"><i class="fa fa-times-circle"></i> Error</h4>
    <p>Please check the form below for errors</p>
    <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
</div>
@endif

@if ($message = Session::get('success'))
<div class="alert alert-block alert-success fade in">
    <a class="close" data-dismiss="alert" aria-hidden="true">&times;</a>
    <h4 class="alert-heading"><i class="fa fa-check-circle"></i> Success</h4>
    @if(is_array($message))
        @foreach ($message as $m)
            {{ $m }}
        @endforeach
    @else
        {{ $message }}
    @endif
</div>
@endif

@if ($message = Session::get('error'))
<div class="alert alert-block alert-danger fade in">
    <a class="close" data-dismiss="alert" aria-hidden="true">&times;</a>
    <h4 class="alert-heading"><i class="fa fa-times-circle"></i> Error</h4>
    @if(is_array($message))
        @foreach ($message as $m)
            {{ $m }}
        @endforeach
    @else
        {{ $message }}
    @endif
</div>
@endif

@if ($message = Session::get('warning'))
<div class="alert alert-block alert-warning fade in">
    <a class="close" data-dismiss="alert" aria-hidden="true">&times;</a>
    <h4 class="alert-heading"><i class="fa fa-exclamation-triangle"></i> Warning</h4>
    @if(is_array($message))
        @foreach ($message as $m)
            {{ $m }}
        @endforeach
    @else
        {{ $message }}
    @endif
</div>
@endif

@if ($message = Session::get('info'))
<div class="alert alert-block alert-info fade in">
    <a class="close" data-dismiss="alert" aria-hidden="true">&times;</a>
    <h4 class="alert-heading"><i class="fa fa-info-circle"></i> Info</h4>
    @if(is_array($message))
        @foreach ($message as $m)
            {{ $m }}
        @endforeach
    @else
        {{ $message }}
    @endif
</div>
@endif