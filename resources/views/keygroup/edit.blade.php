@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('keygroup/title.key_group_update'))

{{-- Content Header --}}
@section('header')
    <i class="fa fa-briefcase"></i> @lang('keygroup/title.key_group_update')
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('keygroups.index') }}">
            @lang('site.key_groups')
        </a>
    </li>
    <li class="breadcrumb-item active">
        @lang('keygroup/title.key_group_update')
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    <!-- Card -->
    <div class="card">

        <x-form :action="route('keygroups.update', $keygroup)" method="PUT">

        <div class="card-header">
            <h2 class="card-title">
                {{ $keygroup->name }}
            </h2>
        </div>

        <div class="card-body">
            <div class="form-row">
                <!-- left column -->
                <div class="col-md-4">

                    <fieldset>
                        <legend>@lang('keygroup/messages.basic_information_section')</legend>

                        <!-- name -->
                        <x-form-input name="name" :label="__('keygroup/model.name')" :default="$keygroup->name" required autofocus>
                            @slot('help')
                                <small class="form-text text-muted">
                                    @lang('keygroup/messages.name_help')
                                </small>
                            @endslot
                        </x-form-input>
                        <!-- ./ name -->

                        <!-- description -->
                        <x-form-textarea name="description" :label="__('keygroup/model.description')" :default="$keygroup->description">
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
                        <legend>@lang('hostgroup/messages.group_members_section')</legend>

                        <!-- key groups -->
                        <x-form-select name="keys[]" :label="__('keygroup/model.hosts')" :options="$keys" multiple class="duallistbox" :default="$keygroup->keys->pluck('id')">
                            @slot('help')
                                <small class="form-text text-muted">
                                    @lang('keygroup/messages.group_help')
                                </small>
                            @endslot
                        </x-form-select>
                        <!-- ./ key groups -->

                    </fieldset>

                    <fieldset class="mt-3">
                        <legend>@lang('keygroup/messages.danger_zone_section')</legend>

                        @can('delete', $keygroup)
                        <ul class="list-group border border-danger">
                            <li class="list-group-item">
                                <strong>@lang('keygroup/messages.delete_button')</strong>
                                <button type="button" class="btn btn-outline-danger btn-sm float-right"
                                        data-toggle="modal"
                                        data-target="#confirmationModal">
                                    @lang('keygroup/messages.delete_button')
                                </button>
                                <p>@lang('keygroup/messages.delete_help')</p>
                            </li>
                        </ul>
                        @else
                            <p class="from-text text-muted">@lang('keygroup/messages.delete_avoided')</p>
                        @endcan
                    </fieldset>

                </div>
                <!-- ./right column -->
            </div>
        </div>
        <div class="card-footer">
            <!-- Form Actions -->
            <x-form-submit class="btn-success">
                @lang('general.update')
            </x-form-submit>

            <a href="{{ route('keygroups.index') }}" class="btn btn-link" role="button">
                @lang('general.cancel')
            </a>
            <!-- ./ form actions -->
        </div>

        </x-form>
    </div>
    <!-- ./ card -->

    @can('delete', $keygroup)
    <!-- confirmation modal -->
    <x-modals.confirmation
        action="{{ route('keygroups.destroy', $keygroup) }}"
        confirmationText="{{ $keygroup->name }}"
        buttonText="{{ __('keygroup/messages.delete_confirmation_button') }}">

        <div class="alert alert-warning" role="alert">
            @lang('keygroup/messages.delete_confirmation_warning', ['name' => $keygroup->name])
        </div>
    </x-modals.confirmation>
    <!-- ./ confirmation modal -->
    @endcan
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

