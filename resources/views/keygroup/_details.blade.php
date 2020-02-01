<div class="card">
    <div class="card-header">
        <h2>{{ $keygroup->name }}</h2>
    </div>
    <div class="card-body">
        <!-- description -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('keygroup/model.description')</strong>
            </div>
            <div class="col-10">
                {{ $keygroup->description }}
            </div>
        </div>
        <!-- ./ description -->

        <!-- groups -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('keygroup/model.keys')</strong>
            </div>
            <div class="col-10">
                <ul>
                    @forelse($keygroup->keys as $key)
                        <li>{{ $key->name }}</li>
                    @empty
                        <li>@lang('keygroup/model.no_keys')</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <!-- ./ groups -->

    </div>
</div>

</div>
<div class="card-footer">
    <a href="{{ route('keygroups.index') }}" class="btn btn-primary" role="button">
        <i class="fa fa-arrow-left"></i> @lang('general.back')
    </a>
    @if ($action == 'show')
        <a href="{{ route('keygroups.edit', $keygroup->id) }}" class="btn btn-primary" role="button">
            <i class="fa fa-pen"></i> @lang('general.edit')
        </a>
    @else
        {!! Form::button('<i class="fa fa-trash"></i> ' . __('general.delete'), array('type' => 'submit', 'class' => 'btn btn-danger')) !!}
    @endif
</div>


