<button type="button" class="btn btn-outline-danger btn-sm @cannot('delete', $rule) disabled @endcannot"
        data-toggle="modal"
        data-target="#confirmationModal"
        data-rule-id="{{ $rule->id }}"
        data-rule-source="{{ $rule->source->present()->name }}"
        data-rule-target="{{ $rule->target->present()->name }}">
    <i class="fa fa-trash"></i> @lang('general.delete')
</button>
