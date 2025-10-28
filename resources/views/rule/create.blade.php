@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('rule/title.create_a_new_rule'))

{{-- Content Header --}}
@section('header')
    @lang('rule/title.create_a_new_rule')
    <small class="text-muted">@lang('rule/title.create_a_new_rule_subtitle')</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('rules.index') }}">
            @lang('site.rules')
        </a>
    </li>
    <li class="breadcrumb-item active">
        @lang('rule/title.create_a_new_rule')
    </li>
@endsection


{{-- Content --}}
@section('content')


    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    <div class="card">

        <x-forms.form :action="route('rules.store')">

        <div class="card-body">
            <div class="form-row">

                <!-- source -->
                <div class="col-md-4">
                    <x-forms.select name="source" :label="__('rule/model.source')" :options="$sources" class="search-select">
                        @slot('help')
                            <small class="form-text text-muted">
                                This is the group of SSH keys which will have access to the target.
                            </small>
                        @endslot
                    </x-forms.select>
                </div>
                <!-- ./ source -->

                <!-- target -->
                <div class="col-md-4">
                    <x-forms.select name="target" :label="__('rule/model.target')" :options="$targets" class="search-select">
                        @slot('help')
                            <small class="form-text text-muted">
                                This is the group of hosts to which you are granting access to.
                            </small>
                        @endslot
                    </x-forms.select>
                </div>
                <!-- ./ target -->

                <!-- name -->
                <div class="col-md-4">
                    <x-forms.input name="name" :label="__('rule/model.name')" required>
                        @slot('help')
                            <small class="form-text text-muted">
                                Short description to identify the rule easily.
                            </small>
                        @endslot
                    </x-forms.input>
                </div>
                <!-- ./ name -->
            </div>
        </div>

        <div class="card-footer">
            <!-- Form Actions -->
            <x-forms.submit>
                @lang('general.create')
            </x-forms.submit>

            <a href="{{ route('rules.index') }}" class="btn btn-link" role="button">
                @lang('general.cancel')
            </a>
            <!-- ./ form actions -->
        </div>

        </x-forms.form>
    </div>
    <!-- ./ card -->
@endsection

{{-- Styles --}}
@push('styles')
    <link rel="stylesheet" type="text/css"
          href="{{ asset('vendor/AdminLTE/plugins/select2/css/select2.min.css') }}">
@endpush

{{-- Scripts --}}
@push('scripts')
    <script src="{{ asset('vendor/AdminLTE/plugins/select2/js/select2.min.js') }}"></script>
    <script>
        $(".search-select").select2({
            placeholder: 'Select a group',
            language: "@lang('site.language_short')",
        });
    </script>
@endpush
