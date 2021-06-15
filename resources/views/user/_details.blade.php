<div class="card">
    <div class="card-header @unless($user->enabled) bg-gray-dark @endunless">
        <div class="card-title">
            <h2 class="card-title">
                {{ $user->present()->username }}
                @unless($user->enabled)
                    {{ $user->present()->enabledAsBadge() }}
                @endunless
            </h2>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <!-- left column -->
            <div class="col-md-6">

                <h3>@lang('user/title.personal_information_section')</h3>
                <dl class="row">

                    <!-- username -->
                    <dt class="col-sm-3">
                        <strong>@lang('user/model.username')</strong>
                    </dt>
                    <dd class="col-sm-9">
                        {{ $user->present()->username }}
                    </dd>
                    <!-- ./username -->

                    <!-- email -->
                    <dt class="col-sm-3">
                        <strong>@lang('user/model.email')</strong>
                    </dt>
                    <dd class="col-sm-9">
                        {{ $user->present()->email }}
                    </dd>
                    <!-- ./email -->

                    <!-- role -->
                    <dt class="col-sm-3">
                        <strong>@lang('user/model.role')</strong>
                    </dt>
                    <dd class="col-sm-9">
                        {{ $user->present()->role }}
                    </dd>
                    <!-- ./role -->

                </dl>
            </div>
            <!-- ./ left column -->
            <!-- right column -->
            <div class="col-md-6">

                <h3>@lang('user/title.status_section')</h3>

                <dl class="row">
                    <!-- created at -->

                    <dt class="col-sm-3">
                        <strong>@lang('user/model.created_at')</strong>
                    </dt>
                    <dd class="col-sm-9">
                        {{ $user->present()->createdAtForHumans() }} ({{ $user->present()->created_at }})
                    </dd>
                    <!-- ./ created at -->

                    <!-- enabled -->
                    <dt class="col-sm-3">
                        <strong>@lang('user/model.enabled')</strong>
                    </dt>
                    <dd class="col-sm-9">
                        {{ $user->present()->enabledAsBadge() }}
                    </dd>
                    <!-- ./ enabled -->

                    <!-- authentication -->
                    <dt class="col-sm-3">
                        <strong>@lang('user/model.authentication')</strong>
                    </dt>
                    <dd class="col-sm-9">
                        {{ $user->present()->authenticationAsBadge() }}
                    </dd>
                    <!-- ./ authentication -->
                </dl>


                <h3>@lang('user/messages.danger_zone_section')</h3>

                @can('delete', $user)
                    <ul class="list-group border border-danger">
                        <li class="list-group-item">
                            <strong>@lang('user/messages.delete_button')</strong>
                            <button type="button" class="btn btn-outline-danger btn-sm float-right"
                                    data-toggle="modal"
                                    data-target="#confirmationModal">
                                @lang('user/messages.delete_button')
                            </button>
                            <p>@lang('user/messages.delete_help')</p>
                        </li>
                    </ul>
                @else
                    <p class="text-muted">@lang('user/messages.delete_avoided')</p>
                @endcan

            </div>
            <!-- ./ right column -->
        </div>
    </div>

    <div class="card-footer">
        <a href="{{ route('users.edit', $user) }}" class="btn btn-primary  @cannot('update', $user) disabled @endcannot"
           role="button">
            @lang('general.edit')
        </a>
        <a href="{{ route('users.index') }}" class="btn btn-link" role="button">
            @lang('general.cancel')
        </a>
    </div>

@can('delete', $user)
    <!-- confirmation modal -->
        <x-modals.confirmation
            action="{{ route('users.destroy', $user) }}"
            confirmationText="{{ $user->username }}"
            buttonText="{{ __('user/messages.delete_confirmation_button') }}">

            <div class="alert alert-warning" role="alert">
                @lang('user/messages.delete_confirmation_warning', ['username' => $user->username])
            </div>
        </x-modals.confirmation>
        <!-- ./ confirmation modal -->
@endcan

