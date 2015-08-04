{{-- Create / Edit User Form --}}

<div class="row">
    <div class="col-xs-6">

        <!-- username -->
        <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
            {!! Form::label('username', Lang::get('user/model.username'), array('class' => 'control-label')) !!}
            <div class="controls">
                @if (isset($user))
                    {!! Form::text('username', null, array('class' => 'form-control', 'disabled' => 'disabled')) !!}
                @else
                    {!! Form::text('username', null, array('class' => 'form-control')) !!}
                @endif
                <span class="help-block">{{ $errors->first('username', ':message') }}</span>
            </div>
        </div>
        <!-- ./ username -->

        <!-- public_key -->
        <div class="form-group {{ $errors->has('public_key') ? 'has-error' : '' }}">
            {!! Form::label('public_key', Lang::get('user/model.public_key'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::textarea('public_key', null, array('class' => 'form-control')) !!}
                <span class="help-block"><i class="fa fa-info-circle"></i> If you leave blank a new SSH key will be created.</span>
                <span class="help-block">{{ $errors->first('public_key', ':message') }}</span>
            </div>
        </div>
        <!-- ./ public_key -->

    </div>
    <div class="col-xs-6">

        <!-- user's groups -->
        <div class="form-group {{ $errors->has('groups[]') ? 'has-error' : '' }}">
            {!! Form::label('groups[]', Lang::get('user/model.groups'), array('class' => 'control-label')) !!}
            <div class="controls">
                @if (isset($user))
                    {!! Form::select('groups[]', $groups, $user->usergroups->lists('id')->all(), array('multiple' => 'multiple', 'class' => 'form-control search-select')) !!}
                @else
                    {!! Form::select('groups[]', $groups, null, array('multiple' => 'multiple', 'class' => 'form-control search-select')) !!}
                @endif
                <span class="help-block">{{ $errors->first('groups[]', ':message') }}</span>
            </div>
        </div>
        <!-- ./ user's groups -->

        @if (isset($user))
            <!-- administrator role -->
            <div class="form-group {{ $errors->has('is_admin') ? 'has-error' : '' }}">
                {!! Form::label('is_admin', Lang::get('user/model.is_admin'), array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::select('is_admin', array('1' => Lang::get('general.yes'), '0' => Lang::get('general.no')), ($user->hasRole('admin') ? '1' : '0'), array('class' => 'form-control', 'disabled' => 'disabled')) !!}
                    <span class="help-block">{{ $errors->first('is_admin', ':message') }}</span>
                </div>
            </div>
            <!-- ./ administrator role -->
        @endif

        @if (isset($user))
            <!-- enabled -->
            <div class="form-group {{ $errors->has('enabled') ? 'has-error' : '' }}">
                {!! Form::label('enabled', Lang::get('user/model.enabled'), array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::select('enabled', array('1' => Lang::get('general.yes'), '0' => Lang::get('general.no')),
                    null, array('class' => 'form-control')) !!}
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
            placeholder: "{!! Lang::get('user/messages.groups_help') !!}",
            allowClear: true,
            language: "{!! Lang::get('site.language_short') !!}"
        });
    </script>
@stop
