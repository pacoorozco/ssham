<div class="card">
    <div class="card-header bg-cyan">
        <h3 class="card-title">{{ $keygroup->name }}</h3>
    </div>
    <div class="card-body">
        <!-- name -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('keygroup/model.name')</strong>
            </div>
            <div class="col-10">
                {{ $keygroup->name }}
            </div>
        </div>
        <!-- ./ name -->

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

        <!-- keys -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('keygroup/model.keys')</strong>
            </div>
            <div class="col-10">
                <p>{{ $keygroup->keys->count() }} @lang('key/model.item')</p>
                <ul class="list-inline">
                    @forelse($keygroup->keys as $key)
                        <li class="list-inline-item"><a href="{{ route('keys.show', $key->id) }}">{{ $key->username }}</a></li>
                    @empty
                        <li class="list-inline-item">@lang('keygroup/model.no_keys')</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <!-- ./ keys -->

        <!-- rules -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('keygroup/model.rules')</strong>
            </div>
            <div class="col-10">
                {{ trans_choice('keygroup/messages.present_in', $keygroup->rules->count(), ['value' => $keygroup->rules->count()]) }}
            </div>
        </div>
        <!-- ./ rules -->

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


