<div class="visible-md visible-lg hidden-sm hidden-xs">
    <a href="{!! route($model . '.destroy', $id) !!}" class="btn btn-xs btn-bricky"
       data-method="delete"
       data-confirm="{{ trans('rule/messages.confirm_delete') }}"
       data-original-title="{{ trans('general.delete') }}"
       rel="nofollow"><i class="fa fa-trash-o"></i></a>
</div>
