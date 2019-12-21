<div class="visible-md visible-lg hidden-sm hidden-xs">
    <a class="btn btn-sm btn-info" href="{{ route($model . '.show', $id) }}">
        <i class="fa fa-eye"></i> {{ __('general.show') }}
    </a>
    <a class="btn btn-sm btn-primary" href="{{ route($model . '.edit', $id) }}">
        <i class="fa fa-edit"></i> {{ __('general.edit') }}
    </a>
    <a class="btn btn-sm btn-danger" href="{{ route($model . '.delete', $id) }}">
        <i class="fa fa-trash"></i> {{ __('general.delete') }}
    </a>
</div>
