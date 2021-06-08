<div class="visible-md visible-lg hidden-sm hidden-xs">
    <a class="btn btn-sm btn-info" href="{{ route($modelType . '.show', $model->id) }}" role="button">
        <i class="fa fa-eye"></i> @lang('general.show')
    </a>
    @can('update', $model)
        <a class="btn btn-sm btn-primary" href="{{ route($modelType . '.edit', $model->id) }}" role="button">
            <i class="fa fa-edit"></i> @lang('general.edit')
        </a>
    @endcan
</div>
