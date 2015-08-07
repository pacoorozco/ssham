{{-- Create / Edit Host Form --}}

<div class="row">
    <div class="col-xs-6">

        @if (isset($host))
            <!-- hostname -->
            <div class="form-group">
                {!! Form::label('hostname', trans('host/model.full_hostname'), array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::text('hostname', $host->getFullHostname(), array('class' => 'form-control', 'disabled' => 'disabled')) !!}
                </div>
            </div>
            <!-- ./ hostname -->
        @else
            <!-- hostname -->
            <div class="form-group {{ $errors->has('hostname') ? 'has-error' : '' }}">
                {!! Form::label('hostname', trans('host/model.hostname'), array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::text('hostname', null, array('class' => 'form-control')) !!}
                    <span class="help-block">{{ $errors->first('hostname', ':message') }}</span>
                </div>
            </div>
            <!-- ./ hostname -->

            <!-- username -->
            <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
                {!! Form::label('username', trans('host/model.username'), array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::text('username', 'root', array('class' => 'form-control')) !!}
                    <span class="help-block">{{ $errors->first('username', ':message') }}</span>
                </div>
            </div>
            <!-- ./ username -->
        @endif

    </div>
    <div class="col-xs-6">

        <!-- host groups -->
        <div class="form-group {{ $errors->has('groups[]') ? 'has-error' : '' }}">
            {!! Form::label('groups[]', trans('host/model.groups'), array('class' => 'control-label')) !!}
            <div class="controls">
                @if (isset($host))
                    {!! Form::select('groups[]', $groups, $host->hostgroups->lists('id')->all(), array('multiple' => 'multiple', 'class' => 'form-control search-select')) !!}
                @else
                    {!! Form::select('groups[]', $groups, null, array('multiple' => 'multiple', 'class' => 'form-control search-select')) !!}
                @endif
                <span class="help-block">{{ $errors->first('groups[]', ':message') }}</span>
            </div>
        </div>
        <!-- ./ host groups -->

        <!-- enabled -->
        <div class="form-group {{ $errors->has('enabled') ? 'has-error' : '' }}">
            {!! Form::label('enabled', trans('host/model.enabled'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::select('enabled', array('1' => trans('general.yes'), '0' => trans('general.no')), null, array('class' => 'form-control')) !!}
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
                {!! Form::button(trans('button.save'), array('type' => 'submit', 'class' => 'btn btn-success')) !!}
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
            placeholder: "{{ trans('host/messages.groups_help') }}",
            allowClear: true,
            language: "{{ trans('site.language_short') }}"
        });
    </script>
@stop

