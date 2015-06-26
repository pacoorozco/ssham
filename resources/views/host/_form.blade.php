{{-- Create / Edit User Form --}}

<div class="row">
    <div class="col-xs-6">

        <!-- hostname -->
        <div class="form-group {{ $errors->has('hostname') ? 'has-error' : '' }}">
            {!! Form::label('hostname', Lang::get('host/model.hostname'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('hostname', null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('hostname', ':message') }}</span>
            </div>
        </div>
        <!-- ./ hostname -->

        <!-- username -->
        <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
            {!! Form::label('username', Lang::get('host/model.username'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('username', null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('username', ':message') }}</span>
            </div>
        </div>
        <!-- ./ username -->
    </div>
    <div class="col-xs-6">

        <!-- hostgroups -->
        <div class="form-group {{ $errors->has('hostgroups[]') ? 'has-error' : '' }}">
            {!! Form::label('hostgroups[]', Lang::get('user/model.hostgroups'), array('class' => 'control-label')) !!}
            <div class="controls">
                @if (isset($host))
                    {!! Form::select('hostgroups[]', $groups, $host->groups->lists('id')->all(), array('multiple' => 'multiple', 'class' => 'form-control search-select')) !!}
                @else
                    {!! Form::select('hostgroups[]', $groups, null, array('multiple' => 'multiple', 'class' => 'form-control search-select')) !!}
                @endif
                <span class="help-block">{{ $errors->first('hostgroups[]', ':message') }}</span>
            </div>
        </div>
        <!-- ./ hostgroups -->

        <!-- enabled -->
        <div class="form-group {{ $errors->has('enabled') ? 'has-error' : '' }}">
            {!! Form::label('enabled', Lang::get('host/model.enabled'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::select('enabled', array('1' => Lang::get('general.yes'), '0' => Lang::get('general.no')), null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('enabled', ':message') }}</span>
            </div>
        </div>
        <!-- ./ enabled -->
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
            placeholder: "{!! Lang::get('user/messages.hostgroups_placeholder') !!}",
            allowClear: true,
            language: "{!! Lang::get('site.language_short') !!}"
        });
    </script>
@stop

