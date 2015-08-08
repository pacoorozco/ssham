
<div class="row">
    <div class="col-xs-12">

        <h2>{{ $usergroup->name }}</h2>

        <!-- description -->
        <strong>@lang('usergroup/model.description')</strong>
        <pre>{{ $usergroup->description }}</pre>
        <!-- ./ description -->

        <!-- groups -->
        <strong>@lang('usergroup/model.users')</strong>
        <pre>
            @foreach($usergroup->users as $user)
                {{ $user->username }}
            @endforeach
        </pre>
        <!-- ./ groups -->
        
    </div>
</div>

<div class="row">
    <div class="col-xs-12">

        <div class="form-group">
            <div class="controls">
                <a href="{!! route('usergroups.index') !!}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> @lang('general.back')</a>
                @if ($action == 'show')
                <a href="{!! route('usergroups.edit', $usergroup->id) !!}" class="btn btn-primary"><i class="fa fa-pencil"></i> @lang('general.edit')</a>
                @else
                {!! Form::button('<i class="fa fa-trash-o"></i> ' . trans('general.delete'), array('type' => 'submit', 'class' => 'btn btn-danger')) !!}
                @endif
            </div>
        </div>

    </div>
</div>
