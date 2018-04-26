
@if(session('errors') || !empty($errors) && (count($errors) > 0))
    @foreach($errors->all() as $error)

        <div class="alert alert-danger alert-fill alert-border-left alert-close alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            {{ $error }}
        </div>

    @endforeach

@endif

@if(session('success'))
<div class="alert alert-aquamarine alert-fill alert-border-left alert-close alert-dismissible fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
    <strong>Success!</strong> {{session('success')}}
</div>

@endif

@if(session('error'))

<div class="alert alert-danger alert-fill alert-border-left alert-close alert-dismissible fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
    {{ session('error') }}

</div>

@endif