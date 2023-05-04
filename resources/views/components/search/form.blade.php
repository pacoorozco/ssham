@props([
'searchString' => null
])

<form method="GET" action="{{ route('search') }}" role="search">
    <div class="input-group">
        <input class="form-control" placeholder="@lang('search/messages.input_help')" autofocus="autofocus" name="q" type="text" value="{{ $searchString }}">
        <div class="input-group-append">
            <button type="submit" class="btn btn-info"><i class="fas fa-search"></i></button>
        </div>
    </div>
</form>
