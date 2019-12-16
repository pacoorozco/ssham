<div class="visible-md visible-lg hidden-sm hidden-xs">
    <a href="{!! route($model . '.show', $id) !!}" class="btn btn-xs btn-teal tooltips"
       data-placement="top" data-original-title="@lang('general.show')"><i class="fa fa-eye"></i></a>
    <a href="{!! route($model . '.edit', $id) !!}" class="btn btn-xs btn-green tooltips"
       data-placement="top" data-original-title="@lang('general.edit')"><i class="fa fa-edit"></i></a>
    <a href="{!! route($model . '.delete', $id) !!}" class="btn btn-xs btn-bricky tooltips"
       data-placement="top" data-original-title="@lang('general.delete')"><i class="fa fa-trash-o"></i></a>
</div>
