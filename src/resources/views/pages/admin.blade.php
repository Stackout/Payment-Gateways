@extends('sgateway::layouts.main')

@section('content')


<div class="container" style="margin-top:50px" id="gatewayTab" role="tablist">
    <h3>Payment Gateway Settings</h3>
    <p>Enter your settings for each gateway.</p>
   
    @include('sgateway::inc.messages')

    <nav style="margin-bottom: 0px;">
        <ul class="nav nav-tabs" role="tablist">

            <li class="nav-item">
                <a href="#stripe" class="nav-link active" id="stripe-tab" data-toggle="tab" role="tab" aria-controls="stripe" aria-selected="true">
                    Stripe Settings
                </a>
            </li>

            <li class="nav-item">
                <a href="#paypal" class="nav-link" id="paypal-tab" data-toggle="tab" role="tab" aria-controls="paypal" aria-selected="false">
                    Paypal Pro Settings
                </a>
            </li>
            
        </ul>
    </nav>

    <div class="tab-content" id="gatewayTabContent" style="border: 1px solid #dee2e6; padding: 25px; background-color:#fff;">

        <!-- Stripe Tabbed Content -->
        <div class="tab-pane fade show active" id="stripe" role="tabpanel" aria-labelledby="stripe-tab">

            <h3 class="form-header">
                Stripe Settings
                <small class="text-muted">All key settings pertaining to production and development keys.</small>
            </h3>

            <form method="POST" action="">
                @csrf

                <h4 style="sub-form-header">Development (Testing)</h4>

                <div class="form-group row">
                    <label for="stripe-test-public-key" class="col-sm-2 col-form-label">Test Public Key</label>
                    <div class="col-sm-10">

                        <input type="text" class="form-control" id="stripe-test-public-key" name="stripe_test_key_public" placeholder="Enter your test Stripe Public Key..." value="{{ (array_key_exists('stripe_test_key_public', $settings) ? $settings['stripe_test_key_public'] : '') }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="stripe-test-private-key" class="col-sm-2 col-form-label">Test Secret Key</label>
                    <div class="col-sm-10">
                        <input type="password" value="{{ (array_key_exists('stripe_test_key_secret', $settings) ? $settings['stripe_test_key_secret'] : '') }}" class="form-control" id="stripe-test-private-key" name="stripe_test_key_secret" placeholder="Enter your test Stripe Private Key...">
                    </div>
                </div>

                <h4 style="sub-form-header">Production</h4>
                <div class="form-group row">
                    <label for="stripe-public-key" class="col-sm-2 col-form-label">Public Key</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="stripe-public-key" name="stripe_key_public" placeholder="Enter your Stripe Public Key..." value="{{ (array_key_exists('stripe_key_public', $settings) ? $settings['stripe_key_public'] : '') }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="stripe-private-key" class="col-sm-2 col-form-label">Secret Key</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="stripe-private-key" name="stripe_key_secret" placeholder="Enter your Stripe Private Key..." value="{{ (array_key_exists('stripe_key_secret', $settings) ? $settings['stripe_key_secret'] : '') }}">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-12">
                        <input type="submit" value="Save" name="save" class="btn btn-primary" id="savebutton">
                    </div>
                </div>
            </form>

        </div>
        <!-- End Stipe Tabbed Content -->

        <!-- Paypal Pro Tabbed Content -->
        <div class="tab-pane fade" id="paypal" role="tabpanel" aria-labelledby="paypal-tab">
            Paypal pro Settings Here
        </div>
        <!-- End Paypal Pro Tabbed Content -->

    </div>

</div>

@endsection


@push('scripts')



@endpush


@push('styles')
<style>
.form-header{
    margin-bottom: 25px;
    padding-bottom: 8px;
}

h4{
    margin-top: 50px;
    margin-bottom: 25px;
    border-bottom: 1px solid #dee2e6;
    padding-bottom: 8px;
}


</style>

@endpush