@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('hostgroup/title.create_a_new_host_group'))

{{-- Content Header --}}
@section('header')
    <i class="fa fa-server"></i> @lang('hostgroup/title.create_a_new_host_group')
    <small class="text-muted">@lang('hostgroup/title.create_a_new_host_group_subtitle')</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('hostgroups.index') }}">
            @lang('site.host_groups')
        </a>
    </li>
    <li class="breadcrumb-item active">
        @lang('hostgroup/title.create_a_new_host_group')
    </li>
@endsection


{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    <div class="card">
        <x-form :action="route('hostgroups.store')">

        <div class="card-body">
            <div class="form-row">
                <!-- left column -->
                <div class="col-md-4">

                    <fieldset>
                        <legend>@lang('hostgroup/messages.basic_information_section')</legend>
                        <!-- name -->
                        <x-form-input name="name" :label="__('hostgroup/model.name')" required autofocus>
                            @slot('help')
                                <small class="form-text text-muted">
                                    @lang('hostgroup/messages.name_help')
                                </small>
                            @endslot
                        </x-form-input>
                        <!-- ./ name -->

                        <!-- description -->
                        <x-form-textarea name="description" :label="__('hostgroup/model.description')">
                            @slot('help')
                                <small class="form-text text-muted">
                                    @lang('hostgroup/messages.description_help')
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
                        <!-- group members -->
                        <x-form-select name="hosts[]" :label="__('hostgroup/model.hosts')" :options="$hosts" multiple class="duallistbox">
                            @slot('help')
                                <small class="form-text text-muted">
                                    @lang('hostgroup/messages.group_help')
                                </small>
                            @endslot
                        </x-form-select>
                        <!-- ./ group members -->
                    </fieldset>
                </div>
                <!-- ./ right column -->
            </div>
        </div>
        <div class="card-footer">
            <!-- Form Actions -->
            <x-form-submit class="btn-success">
                @lang('general.create')
            </x-form-submit>

            <a href="{{ route('hostgroups.index') }}" class="btn btn-link" role="button">
                @lang('general.cancel')
            </a>
            <!-- ./ form actions -->
        </div>

        </x-form>
    </div>
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

