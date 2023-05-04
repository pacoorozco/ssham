@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('keygroup/title.create_a_new_key_group'))

{{-- Content Header --}}
@section('header')
    <i class="fa fa-briefcase"></i> @lang('keygroup/title.create_a_new_key_group')
    <small class="text-muted">@lang('keygroup/title.create_a_new_key_group_subtitle')</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('keygroups.index') }}">
            @lang('site.key_groups')
        </a>
    </li>
    <li class="breadcrumb-item active">
        @lang('keygroup/title.create_a_new_key_group')
    </li>
@endsection


{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    <!-- Card -->
    <div class="card">

        <x-form :action="route('keygroups.store')">

        <div class="card-body">
            <div class="form-row">
                <!-- left column -->
                <div class="col-md-4">

                    <fieldset>
                        <legend>@lang('keygroup/messages.basic_information_section')</legend>

                        <!-- name -->
                        <x-form-input name="name" :label="__('keygroup/model.name')" required autofocus>
                            @slot('help')
                                <small class="form-text text-muted">
                                    @lang('keygroup/messages.name_help')
                                </small>
                            @endslot
                        </x-form-input>
                        <!-- ./ name -->

                        <!-- description -->
                        <x-form-textarea name="description" :label="__('keygroup/model.description')">
                            @slot('help')
                                <small class="form-text text-muted">
                                    @lang('keygroup/messages.description_help')
                                </small>
                            @endslot
                        </x-form-textarea>
                        <!-- ./ description -->
                    </fieldset>
                </div>
                <!-- ./ left column -->

                <!-- right column -->
                <div class="col-md-8">
                    <fieldset>
                        <legend>@lang('keygroup/messages.group_members_section')</legend>
                        <!-- key's groups -->
                        <x-form-select name="keys[]" :label="__('keygroup/model.keys')" :options="$keys" multiple class="duallistbox">
                            @slot('help')
                                <small class="form-text text-muted">
                                    @lang('keygroup/messages.group_help')
                                </small>
                            @endslot
                        </x-form-select>
                        <!-- ./ key's groups -->
                    </fieldset>
                </div>
                <!-- ./right column -->
            </div>
        </div>
        <div class="card-footer">
            <!-- Form Actions -->
            <x-form-submit class="btn-success">
                @lang('general.create')
            </x-form-submit>

            <a href="{{ route('keygroups.index') }}" class="btn btn-link" role="button">
                @lang('general.cancel')
            </a>
            <!-- ./ form actions -->
        </div>

        </x-form>
    </div>
    <!-- ./ card -->
@endsection

{{-- Styles --}}
@push('styles')
    <link rel="stylesheet" type="text/css"
          href="{{ asset('vendor/AdminLTE/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') }}">
@endpush

{{-- Scripts --}}
@push('scripts')
    <script
        src="{{ asset('vendor/AdminLTE/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>
    <script>
        $('.duallistbox').bootstrapDualListbox({
            nonSelectedListLabel: '{{ __('keygroup/messages.available_keys_section') }}',
            selectedListLabel: '{{ __('keygroup/messages.selected_keys_section') }}',
        });
    </script>
@endpush

