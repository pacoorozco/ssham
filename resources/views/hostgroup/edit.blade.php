@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('hostgroup/title.host_group_update'))

{{-- Content Header --}}
@section('header')
    <i class="fa fa-server"></i> @lang('hostgroup/title.host_group_update')
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('hostgroups.index') }}">
            @lang('site.host_groups')
        </a>
    </li>
    <li class="breadcrumb-item active">
        @lang('hostgroup/title.host_group_update')
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    <div class="card">

        <x-forms.form :action="route('hostgroups.update', $hostgroup)" method="PUT">

        <div class="card-header">
            <h2 class="card-title">
                {{ $hostgroup->name }}
            </h2>
        </div>

        <div class="card-body">
            <div class="form-row">
                <!-- left column -->
                <div class="col-md-4">

                    <fieldset>
                        <legend>@lang('hostgroup/messages.basic_information_section')</legend>
                        <!-- name -->
                        <x-forms.input name="name" :label="__('hostgroup/model.name')" :default="$hostgroup->name" required autofocus>
                            @slot('help')
                                <small class="form-text text-muted">
                                    @lang('hostgroup/messages.name_help')
                                </small>
                            @endslot
                        </x-forms.input>
                        <!-- ./ name -->

                        <!-- description -->
                        <x-forms.textarea name="description" :label="__('hostgroup/model.description')" :default="$hostgroup->description">
                            @slot('help')
                                <small class="form-text text-muted">
                                    @lang('hostgroup/messages.description_help')
                                </small>
                            @endslot
                        </x-forms.textarea>
                        <!-- ./ description -->
                    </fieldset>
                </div>
                <!-- ./ left column -->

                <!-- right column -->
                <div class="col-md-8">
                    <fieldset>
                        <legend>@lang('hostgroup/messages.group_members_section')</legend>
                        <!-- host's groups -->
                        <x-forms.select name="hosts[]" :label="__('hostgroup/model.hosts')" :options="$hosts" multiple class="duallistbox" :default="$hostgroup->hosts->pluck('id')">
                            @slot('help')
                                <small class="form-text text-muted">
                                    @lang('hostgroup/messages.group_help')
                                </small>
                            @endslot
                        </x-forms.select>
                        <!-- ./ host's groups -->
                    </fieldset>

                    <fieldset class="mt-3">
                        <legend>@lang('hostgroup/messages.danger_zone_section')</legend>

                        @can('delete', $hostgroup)
                        <ul class="list-group border border-danger">
                            <li class="list-group-item">
                                <strong>@lang('hostgroup/messages.delete_button')</strong>
                                <button type="button" class="btn btn-outline-danger btn-sm float-right"
                                        data-toggle="modal"
                                        data-target="#confirmationModal">
                                    @lang('hostgroup/messages.delete_button')
                                </button>
                                <p>@lang('hostgroup/messages.delete_help')</p>
                            </li>
                        </ul>
                        @else
                            <p class="from-text text-muted">@lang('hostgroup/messages.delete_avoided')</p>
                        @endcan
                    </fieldset>
                </div>
                <!-- ./right column -->
            </div>
        </div>
        <div class="card-footer">
            <!-- Form Actions -->
            <x-forms.submit class="btn-success">
                @lang('general.update')
            </x-forms.submit>

            <a href="{{ route('hostgroups.index') }}" class="btn btn-link" role="button">
                @lang('general.cancel')
            </a>
            <!-- ./ form actions -->
        </div>

        </x-forms.form>
    </div>

    @can('delete', $hostgroup)
        <!-- confirmation modal -->
        <x-modals.confirmation
            action="{{ route('hostgroups.destroy', $hostgroup) }}"
            confirmationText="{{ $hostgroup->name }}"
            buttonText="{{ __('hostgroup/messages.delete_confirmation_button') }}">

            <div class="alert alert-warning" role="alert">
                @lang('hostgroup/messages.delete_confirmation_warning', ['name' => $hostgroup->name])
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
            nonSelectedListLabel: '{{ __('hostgroup/messages.available_hosts_section') }}',
            selectedListLabel: '{{ __('hostgroup/messages.selected_hosts_section') }}',
        });
    </script>
@endpush

