{{-- Create / Edit Host Groups Form --}}

<div class="row">
    <div class="col-xs-6">

        <!-- name -->
        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
            {!! Form::label('name', trans('hostgroup/model.name'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('name', null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('name', ':message') }}</span>
            </div>
        </div>
        <!-- ./ name -->

        <!-- description -->
        <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
            {!! Form::label('description', trans('hostgroup/model.description'), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::textarea('description', null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('description', ':message') }}</span>
            </div>
        </div>
        <!-- ./ description -->

    </div>
    <div class="col-xs-6">

        <!-- hosts -->
        <div class="form-group {{ $errors->has('hosts[]') ? 'has-error' : '' }}">
            {!! Form::label('hosts[]', trans('hostgroup/model.hosts'), array('class' => 'control-label')) !!}
            <div class="controls">
                @if (isset($hostgroup))
                    {!! Form::select('hosts[]', $hosts, $hostgroup->hosts->lists('id')->all(), array('multiple' => 'multiple', 'class' => 'form-control search-select')) !!}
                @else
                    {!! Form::select('hosts[]', $hosts, null, array('multiple' => 'multiple', 'class' => 'form-control search-select')) !!}
                @endif
                <span class="help-block">{{ $errors->first('hosts[]', ':message') }}</span>
            </div>
        </div>
        <!-- ./ hosts -->
    </div>
</div>

<div class="row">
    <div class="col-xs-12">

        <!-- Form Actions -->
        <div class="form-group">
            <div class="controls">
                {!! Form::button('<i class="fa fa-floppy-o"></i> ' . trans('general.save'), array('type' => 'submit', 'class' => 'btn btn-success')) !!}
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
            placeholder: "@lang('hostgroup/messages.hosts_placeholder')",
            allowClear: true,
            language: "@lang('site.language_short')"
        });
    </script>
@stop
