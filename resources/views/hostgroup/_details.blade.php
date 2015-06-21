
<div class="row">
    <div class="col-xs-12">

        <dl class="dl-horizontal">

        <!-- name -->
        <dt>{!! Lang::get('hostgroup/model.name') !!}</dt>
        <dd>{{ $hostgroup->name }}</dd>
        <!-- ./ name -->

        <!-- description -->
        <dt>{!! Lang::get('hostgroup/model.description') !!}</dt>
        <dd>{{ $hostgroup->description }}</dd>
        <!-- ./ description -->

        </dl>
        
    </div>
</div>

<div class="row">
    <div class="col-xs-12">

        <div class="form-group">
            <div class="controls">
                <a href="{!! route('hostgroups.index') !!}" class="btn btn-primary">{!! Lang::get('button.back') !!}</a>
                @if ($action == 'show')
                <a href="{!! route('hostgroups.edit', $hostgroup->id) !!}" class="btn btn-primary">{!! Lang::get('button.edit') !!}</a>
                @else
                {!! Form::button(Lang::get('button.delete'), array('type' => 'submit', 'class' => 'btn btn-danger')) !!}
                @endif
            </div>
        </div>

    </div>
</div>
