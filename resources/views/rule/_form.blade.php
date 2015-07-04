{{-- Create / Edit Rule Form --}}

<div class="row">
    <div class="col-xs-6">

        <!-- usergroup -->
        <div class="form-group {{ $errors->has('usergroup_id') ? 'has-error' : '' }}">
            {!! Form::label('usergroup_id', Lang::get('rule/model.usergroup'), array('class' => 'control-label')) !!}
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
            {!! Form::label('hostgroup_id', Lang::get('rule/model.hostgroup'), array('class' => 'control-label')) !!}
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

        <!-- permission -->
        <div class="form-group {{ $errors->has('permission') ? 'has-error' : '' }}">
            {!! Form::label('permission', Lang::get('rule/model.permission'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::select('permission', array('allow' => Lang::get('rule/model.permission_allow'), 'deny' => Lang::get('rule/model.permission_deny')), null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('permission', ':message') }}</span>
            </div>
        </div>
        <!-- ./ permission -->

        <!-- Activation Status -->
        <div class="form-group {{ $errors->has('active') ? 'has-error' : '' }}">
            {!! Form::label('active', Lang::get('user/model.active'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::select('active', array('1' => Lang::get('general.yes'), '0' => Lang::get('general.no')), null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('active', ':message') }}</span>
            </div>
        </div>
        <!-- ./ activation status -->

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
