{{-- Create / Edit User Form --}}

<div class="card-body">
    <div class="form-row">
        <!-- left column -->
        <div class="col-md-6">

            <!-- username -->
            <div class="form-group">
                <label for="username">@lang('user/model.username')</label>
                @if (isset($user))
                    <input id="username" type="text" class="form-control" name="username"
                           value="{{ $user->username }}" disabled>
                @else
                    <input id="username" type="text"
                           class="form-control @error('username') is-invalid @enderror" name="username"
                           value="{{ old('username') }}" required autofocus>
                @endif
                @error('username')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <!-- ./ username -->

            <!-- SSH public key -->
            <div class="form-group">
                <label for="create_rsa_key">@lang('user/model.public_key')</label>
                <!-- create RSA key -->
                <div class="form-check">
                    @if (isset($user))
                        <input id="create_rsa_key" class="form-check-input" type="radio" name="create_rsa_key"
                               value="1">
                        <label class="form-check-label">@lang('user/messages.create_rsa_key')</label>
                        <span class="form-text text-muted">@lang('user/messages.create_rsa_key_help')</span>
                        <span class="form-text text-muted">@lang('user/messages.create_rsa_key_help_notice')</span>
                    @else
                        <input id="create_rsa_key" class="form-check-input" type="radio" name="create_rsa_key" value="1"
                               checked>
                        <label class="form-check-label">@lang('user/messages.create_rsa_key')</label>
                        <div id="create_rsa_key_form">
                            <span class="form-text text-muted">@lang('user/messages.create_rsa_key_help')</span>
                        </div>
                    @endif
                </div>
                <!-- ./ create RSA key -->

                <!-- import / edit public_key -->
                <div class="form-check">
                    @if (isset($user))
                        <input id="create_rsa_key_false" class="form-check-input" type="radio" name="create_rsa_key">
                        <label class="form-check-label">@lang('user/messages.import_rsa_key')</label>
                        <div id="import_rsa_key_form" class="d-none">
                            <span class="form-text text-muted">@lang('user/messages.import_rsa_key_help')</span>
                            <textarea id="public_key_input"
                                      class="form-control @error('public_key') is-invalid @enderror"
                                      name="public_key"></textarea>
                        </div>
                    @else
                        <input id="import_rsa_key" class="form-check-input" type="radio" name="create_rsa_key">
                        <label class="form-check-label">@lang('user/messages.import_rsa_key')</label>
                        <div id="import_rsa_key_form" class="d-none">
                            <span class="form-text text-muted">@lang('user/messages.import_rsa_key_help')</span>
                            <textarea id="public_key_input"
                                      class="form-control @error('public_key') is-invalid @enderror"
                                      name="public_key" disabled="disabled"></textarea>
                        </div>
                    @endif
                    @error('public_key'))
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <!-- ./ import / edit public_key -->
            </div>
            <!-- ./ SSH public key -->
        </div>
        <!-- ./ left column -->

        <!-- right column -->
        <div class="col-md-6">

            <!-- user's groups -->
            <div class="form-group">
                <label>@lang('user/model.groups')</label>
                <div class="controls">
                    @if (isset($user))
                        <select class="form-control search-select" name="groups[]" multiple="multiple">
                            @foreach($groups as $group)
                                <option>{{ $group }}</option>
                            @endforeach
                        </select>
                        {!! Form::select('groups[]', $groups, $user->usergroups->lists('id')->all(), array('multiple' => 'multiple', 'class' => 'form-control search-select')) !!}
                    @else
                        <select class="form-control search-select" name="groups[]" multiple="multiple">
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    @endif
                    @error('groups[]'))
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <!-- ./ user's groups -->

        @if (isset($user))
            <!-- administrator role -->
                <div class="form-group">
                    <label for="is_admin">@lang('user/model.is_admin')</label>
                    <select class="form-control" name="is_admin" disabled="disabled">
                        <option value="1" {{ $user->hasRole('admin') ? 'selected' : '' }}>@lang('general.yes')</option>
                        <option value="0">@lang('general.no')</option>
                    </select>
                    @error('is_admin'))
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <!-- ./ administrator role -->
        @endif

        @if (isset($user))
            <!-- enabled -->
                <div class="form-group">
                    <label for="enabled">@lang('user/model.enabled')</label>
                    <select class="form-control" name="enabled">
                        <option value="1">@lang('general.yes')</option>
                        <option value="0">@lang('general.no')</option>
                    </select>
                    @error('enabled'))
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <!-- ./ enabled -->
            @endif

        </div>
        <!-- ./right column -->

    </div>
</div>
<div class="card-footer">
    <!-- Form Actions -->
    <div class="form-group">
        <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> @lang('general.save')</button>
    </div>
    <!-- ./ form actions -->
</div>

{{-- Styles --}}
@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/AdminLTE/plugins/select2/css/select2.min.css') }}">
@endpush

{{-- Scripts --}}
@push('scripts')
    <script src="{{ asset('vendor/AdminLTE/plugins/select2/js/select2.min.js') }}"></script>
    <script>
        $(".search-select").select2({
            placeholder: "@lang('user/messages.groups_help')",
            allowClear: true,
            language: "@lang('site.language_short')"
        });

        $("#create_rsa_key").click(function () {
            $("#create_rsa_key_form").removeClass("d-none");
            $("#import_rsa_key_form").addClass("d-none");
            $("#public_key_input").attr("disabled", "disabled");
        });

        $("#import_rsa_key").click(function () {
            $("#import_rsa_key_form").removeClass("d-none");
            $("#create_rsa_key_form").addClass("d-none");
            $("#public_key_input").removeAttr("disabled");
        });
    </script>
@endpush


