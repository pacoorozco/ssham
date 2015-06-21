
<div class="row">
    <div class="col-xs-12">

        <dl class="dl-horizontal">

        <!-- hostname -->
        <dt>{!! Lang::get('host/model.hostname') !!}</dt>
        <dd>{{ $host->hostname }}</dd>
        <!-- ./ hostname -->

        <!-- username -->
        <dt>{!! Lang::get('host/model.username') !!}</dt>
        <dd>{{ $host->username }}</dd>
        <!-- ./ fingerprint -->

        <!-- enabled -->
        <dt>{!! Lang::get('host/model.confirm') !!}</dt>
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
