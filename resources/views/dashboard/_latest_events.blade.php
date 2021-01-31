<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('activity/model.audit_header') }}</h3>
        <div class="card-tools">
            <a class="btn btn-primary" role="button" href="#">{{ __('site.audit') }}</a>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>{{ __('activity/model.operation') }}</th>
                <th>{{ __('activity/model.status') }}</th>
                <th>{{ __('activity/model.time') }}</th>
                <th>{{ __('activity/model.timestamp') }}</th>
                <th>{{ __('activity/model.causer') }}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($activities as $activity)
                <tr>
                    <td>{{ $activity->present()->description }}</td>
                    <td>{{ $activity->present()->statusBadge }}</td>
                    <td>{{ $activity->present()->activityAge }}</td>
                    <td>{{ $activity->present()->created_at }}</td>
                    <td>{{ $activity->present()->causerUsername }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">{{ __('activity/model.warning_no_activity') }}</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
