@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('user/personal_access_token.title'))

{{-- Content Header --}}
@section('header')
    @lang('user/personal_access_token.title')
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('users.index') }}">
            @lang('site.users')
        </a>
    </li>
    <li class="breadcrumb-item">
        @lang('user/title.user_update')
    </li>
    <li class="breadcrumb-item active">
        @lang('user/personal_access_token.title')
    </li>
@endsection

{{-- Content --}}
@section('content')
    <div class="container-fluid">

        <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

        <div class="row">
            <!-- User edit sidebar -->
            <div class="col-md-2">
                @include('user._settings_menu')
            </div>
            <!-- ./ User edit sidebar -->

            <div class="col-md-10">
                <!-- card -->
                <div class="card card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            @lang('user/personal_access_token.title')
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('users.tokens.create', $user) }}" class="btn btn-block btn-outline-success btn-sm">
                                @lang('user/personal_access_token.generate_button')
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @forelse($user->tokens as $token)
                        @if ($loop->first)
                            <p>@lang('user/personal_access_token.list_help')</p>
                            <ul class="list-group">
                        @endif

                        @if ($loop->last)
                            </ul>
                        @endif

                        <li class="list-group-item">
                            <div class="float-right">
                                <button class="btn btn-outline-danger btn-sm" data-toggle="modal"
                                        data-target="#confirmationModal"
                                        data-form-action="{{ route('tokens.destroy', $token->id) }}"
                                        data-token-name="{{ $token->name }}">
                                    @lang('user/personal_access_token.revoke_button')
                                </button>
                            </div>
                            <small class="text-muted float-right">
                                {{ $token->getLastUsedDateString() }}
                            </small>
                            {{ $token->name }}
                        </li>
                        @empty
                            <p>@lang('user/personal_access_token.empty_list_text', ['url' => route('users.tokens.create', $user)])</p>
                        @endforelse

                        <small class="text-muted">
                            @lang('user/personal_access_token.help')
                        </small>
                    </div>
                </div>
                <!-- ./ card -->

                <!-- confirmation modal -->
                <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog"
                     aria-labelledby="confirmationModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="" method="post" id="deleteForm">
                                @csrf
                                @method('DELETE')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmationModalLabel">
                                        @lang('user/personal_access_token.confirmation_title')
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="alert alert-warning" role="alert">
                                        @lang('user/personal_access_token.confirmation_warning')
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-block btn-outline-danger">
                                        @lang('user/personal_access_token.confirmation_button')
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- ./ confirmation modal -->
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            $('#confirmationModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var form_action = button.data('form-action')
                var modal = $(this)
                modal.find('.modal-content #deleteForm').attr('action', form_action);
            });
        });
    </script>
@endpush

