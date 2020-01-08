<div class="visible-md visible-lg hidden-sm hidden-xs">
    {{--
    // This will enable enable / disable rules on the table

    {!! Form::open(['route' => ['rules.update', $rule->id], 'method' => 'put']) !!}
    @if ($rule->enabled === 1)
        {!! Form::hidden('enabled', 0) !!}
        {!! Form::button('<i class="fa fa-check-square"></i>', array('type' => 'submit', 'class' => 'btn btn-xs btn-success')) !!}
    @else
        {!! Form::hidden('enabled', 1) !!}
        {!! Form::button('<i class="fa fa-square"></i>', array('type' => 'submit', 'class' => 'btn btn-xs btn-success')) !!}
    @endif
    {!! Form::close() !!}
    --}}
    <button type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#deleteModal"
            data-rule-id="{{ $rule->id }}">
        <i class="fa fa-trash"></i>
    </button>
</div>

