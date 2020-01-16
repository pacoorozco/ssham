<div class="card">
    <div class="card-header">
        <h2>{{ $usergroup->name }}</h2>
    </div>
    <div class="card-body">
        <!-- description -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('usergroup/model.description')</strong>
            </div>
            <div class="col-10">
                {{ $usergroup->description }}
            </div>
        </div>
        <!-- ./ description -->

        <!-- groups -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('usergroup/model.users')</strong>
            </div>
            <div class="col-10">
                <ul>
                    @forelse($usergroup->users as $user)
                        <li>{{ $user->username }}</li>
                    @empty
                        <li>@lang('usergroup/model.no_users')</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <!-- ./ groups -->

    </div>
</div>

</div>
<div class="card-footer">
    <a href="{{ route('usergroups.index') }}" class="btn btn-primary" role="button">
        <i class="fa fa-arrow-left"></i> @lang('general.back')
    </a>
    @if ($action == 'show')
        <a href="{{ route('usergroups.edit', $usergroup->id) }}" class="btn btn-primary" role="button">
            <i class="fa fa-pen"></i> @lang('general.edit')
        </a>
    @else
        {!! Form::button('<i class="fa fa-trash"></i> ' . __('general.delete'), array('type' => 'submit', 'class' => 'btn btn-danger')) !!}
    @endif
</div>


