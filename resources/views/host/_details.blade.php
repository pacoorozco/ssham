
<div class="row">
    <div class="col-xs-12">

        <dl class="dl-horizontal">

        <!-- full hostname -->
        <dt>{!! Lang::get('host/model.full_hostname') !!}</dt>
        <dd>{{ $host->getFullHostname() }}</dd>
        <!-- ./ full hostname -->

        <!-- enabled -->
        <dt>{!! Lang::get('host/model.enabled') !!}</dt>
        <dd>{{ ($host->enabled) ? Lang::get('general.yes') : Lang::get('general.no') }}</dd>
        <!-- ./ enabled -->

        </dl>
        
    </div>
</div>

<div class="row">
    <div class="col-xs-12">

        <div class="form-group">
            <div class="controls">
                <a href="{!! route('hosts.index') !!}" class="btn btn-primary">{!! Lang::get('button.back') !!}</a>
                @if ($action == 'show')
                <a href="{!! route('hosts.edit', $host->id) !!}" class="btn btn-primary">{!! Lang::get('button.edit') !!}</a>
                @else
                {!! Form::button(Lang::get('button.delete'), array('type' => 'submit', 'class' => 'btn btn-danger')) !!}
                @endif
            </div>
        </div>

    </div>
</div>
