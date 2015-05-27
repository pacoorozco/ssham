<div class="btn-group">
    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
        {!! Lang::get('button.actions') !!}
    </button>
    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu">
        <li><a href="{!! route($model . '.show', $id) !!}">{!! Lang::get('button.show') !!}</a></li>
        <li><a href="{!! route($model . '.edit', $id) !!}">{!! Lang::get('button.edit') !!}</a></li>
        <li><a href="{!! route($model . '.delete', $id) !!}">{!! Lang::get('button.delete') !!}</a></li>
    </ul>
</div>

