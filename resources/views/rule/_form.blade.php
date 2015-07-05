{{-- Create / Edit Rule Form --}}

<div class="row">
    <div class="col-xs-6">

        <!-- usergroup -->
        <div class="form-group {{ $errors->has('usergroup_id') ? 'has-error' : '' }}">
            {!! Form::label('usergroup_id', Lang::get('rule/model.user_group'), array('class' => 'control-label')) !!}
            <div class="controls">
                @if (isset($rule))
                    {!! Form::select('usergroup_id', $usergroups, $rule->usergroup_id, array('class' => 'form-control search-select')) !!}
                @else
                    {!! Form::select('usergroup_id', $usergroups, null, array('class' => 'form-control search-select')) !!}
                @endif
                <span class="help-block">{{ $errors->first('usergroup_id', ':message') }}</span>
            </div>
        </div>
        <!-- ./ usergroup -->

        <!-- hostgroup -->
        <div class="form-group {{ $errors->has('hostgroup_id') ? 'has-error' : '' }}">
            {!! Form::label('hostgroup_id', Lang::get('rule/model.host_group'), array('class' => 'control-label')) !!}
            <div class="controls">
                @if (isset($rule))
                    {!! Form::select('hostgroup_id', $hostgroups, $rule->hostgroup_id, array('class' => 'form-control search-select')) !!}
                @else
                    {!! Form::select('hostgroup_id', $hostgroups, null, array('class' => 'form-control search-select')) !!}
                @endif
                <span class="help-block">{{ $errors->first('hostgroup_id', ':message') }}</span>
            </div>
        </div>
        <!-- ./ hostgroup -->

    </div>
    <div class="col-xs-6">

        <!-- action -->
        <div class="form-group {{ $errors->has('action') ? 'has-error' : '' }}">
            {!! Form::label('action', Lang::get('rule/model.action'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::select('action', array('allow' => Lang::get('rule/model.action_allow'), 'deny' => Lang::get('rule/model.action_deny')), null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('action', ':message') }}</span>
            </div>
        </div>
        <!-- ./ action -->

        <!-- description -->
        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
            {!! Form::label('description', Lang::get('rule/model.description'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::textarea('description', null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('description', ':message') }}</span>
            </div>
        </div>
        <!-- ./ description -->

        @if (isset($rule))
        <!-- enabled -->
        <div class="form-group {{ $errors->has('enabled') ? 'has-error' : '' }}">
            {!! Form::label('enabled', Lang::get('user/model.enabled'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::select('enabled', array('1' => Lang::get('general.yes'), '0' => Lang::get('general.no')), null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('enabled', ':message') }}</span>
            </div>
        </div>
        <!-- ./ enabled -->
        @endif

    </div>
</div>

<div class="row">
    <div class="col-xs-12">

        <!-- Form Actions -->
        <div class="form-group">
            <div class="controls">
                {!! Form::button(Lang::get('button.save'), array('type' => 'submit', 'class' => 'btn btn-success')) !!}
            </div>
        </div>
        <!-- ./ form actions -->

    </div>
</div>

{{-- Styles --}}
@section('styles')
    {!! HTML::style(asset('plugins/select2/select2.css')) !!}
@stop

{{-- Scripts --}}
@section('scripts')
    {!! HTML::script(asset('plugins/select2/select2.min.js')) !!}
    <script>
        $(".search-select").select2({
            placeholder: "{!! Lang::get('rule/messages.usergroups_placeholder') !!}",
            allowClear: true,
            language: "{!! Lang::get('site.language_short') !!}"
        });
    </script>
@stop
