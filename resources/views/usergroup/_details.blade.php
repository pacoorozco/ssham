
<div class="row">
    <div class="col-xs-12">

        <dl class="dl-horizontal">

        <!-- name -->
        <dt>{!! Lang::get('usergroup/model.name') !!}</dt>
        <dd>{{ $usergroup->name }}</dd>
        <!-- ./ name -->

        <!-- description -->
        <dt>{!! Lang::get('usergroup/model.description') !!}</dt>
        <dd>{{ $usergroup->description }}</dd>
        <!-- ./ description -->

        </dl>
        
    </div>
</div>

<div class="row">
    <div class="col-xs-12">

        <div class="form-group">
            <div class="controls">
                <a href="{!! route('usergroups.index') !!}" class="btn btn-primary">{!! Lang::get('button.back') !!}</a>
                @if ($action == 'show')
                <a href="{!! route('usergroups.edit', $usergroup->id) !!}" class="btn btn-primary">{!! Lang::get('button.edit') !!}</a>
                @else
                {!! Form::button(Lang::get('button.delete'), array('type' => 'submit', 'class' => 'btn btn-danger')) !!}
                @endif
            </div>
        </div>

    </div>
</div>
