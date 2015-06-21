@include('flash::message')

@if (count($errors) > 0)
<div class="alert alert-block alert-danger fade in">
    <a class="close" data-dismiss="alert" aria-hidden="true">&times;</a>
    <p>Please check the form below for errors</p>
    <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
</div>
@endif