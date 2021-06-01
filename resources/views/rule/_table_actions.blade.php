<button type="button" class="btn btn-outline-danger btn-sm"
        data-toggle="modal"
        data-target="#confirmationModal"
        data-rule-id="{{ $rule->id }}"
        data-rule-source="{{ $rule->source->present()->name }}"
        data-rule-target="{{ $rule->target->present()->name }}"
        data-rule-action="{{ $rule->present()->action }}">
    <i class="fa fa-trash"></i> @lang('general.delete')
</button>
