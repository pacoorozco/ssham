{{-- Create / Edit Usergroup Form --}}

<div class="row">
    <div class="col-xs-6">

        <!-- name -->
        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
            {!! Form::label('name', trans('usergroup/model.name'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('name', null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('name', ':message') }}</span>
            </div>
        </div>
        <!-- ./ name -->

        <!-- description -->
        <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
            {!! Form::label('description', trans('usergroup/model.description'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::textarea('description', null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('description', ':message') }}</span>
            </div>
        </div>
        <!-- ./ description -->

    </div>
    <div class="col-xs-6">

        <!-- users -->
        <div class="form-group {{ $errors->has('users[]') ? 'has-error' : '' }}">
            {!! Form::label('users[]', trans('usergroup/model.users'), array('class' => 'control-label')) !!}
            <div class="controls">
                @if (isset($usergroup))
                    {!! Form::select('users[]', $users, $usergroup->users->lists('id')->all(), array('multiple' => 'multiple', 'class' => 'form-control search-select')) !!}
                @else
                    {!! Form::select('users[]', $users, null, array('multiple' => 'multiple', 'class' => 'form-control search-select')) !!}
                @endif
                <span class="help-block">{{ $errors->first('users[]', ':message') }}</span>
            </div>
        </div>
        <!-- ./ users -->
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
            placeholder: "{!! trans('usergroup/messages.users_placeholder') !!}",
            allowClear: true,
            language: "{!! trans('site.language_short') !!}"
        });
    </script>
@stop
