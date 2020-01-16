<div class="visible-md visible-lg hidden-sm hidden-xs">
    <a class="btn btn-sm btn-info" href="{{ route($model . '.show', $id) }}" role="button">
        <i class="fa fa-eye"></i> @lang('general.show')
    </a>
    <a class="btn btn-sm btn-primary" href="{{ route($model . '.edit', $id) }}" role="button">
        <i class="fa fa-edit"></i> @lang('general.edit')
    </a>
    <a class="btn btn-sm btn-danger" href="{{ route($model . '.delete', $id) }}" role="button">
        <i class="fa fa-trash"></i> @lang('general.delete')
    </a>
</div>
