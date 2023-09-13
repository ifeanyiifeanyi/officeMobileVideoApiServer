@extends('admin.layouts/adminlayout')


@section('title', 'Create subscription for user')
@section('adminlayout')

<div class="container-fluid py-4" style="height:100vh">

    <div class="row mt-4">
        <div class="col-lg-12 mb-lg-0 mb-4">
            <div class="card ">
                <div class="card-header pb-0 p-3">
                    <div class="d-flex justify-content-between">
                       
                        <h4 class="mb-2">Confirm Subscription Option</h4>
                        <p style="float: right"><a class="btn btn-outline-primary"
                                href="{{ route('users.all') }}"><i class="fas fa-arrow-left"></i> Users</a></p>
                    </div>
                </div>
                <div class="card-body">
                <table class="table">
                    <tr>
                        <td>Subscription Plan</td>
                        <td>{{ $subscription->name }}</td>
                    </tr>
                    <tr>
                        <td>Subscription Amount</td>
                        <td>{{ $subscription->amount }}</td>
                    </tr>
                    <tr>
                        <td>Subscription Duration</td>
                        <td>{{ $subscription->duration_in_name }}</td>
                    </tr>
                    <tr>
                        <td>User</td>
                        <td>{{ $user->name }} | {{ $user->username }}</td>
                    </tr>
                    <tr>
                        <td>User Email</td>
                        <td>{{ $user->email }}</td>
                    </tr>
                </table>
                    <form role="form" method="POST" action="{{ route('subscription.select.pay') }}">
                        <input type="hidden" name="paymentPlanId" value="{{ $subscription->id }}" />
                        <input type="hidden" name="duration" value="{{ $subscription->duration_in_number }}" />
                        <input type="hidden" name="amount" value="{{ $subscription->amount }}" />

                        <input type="hidden" name="userId" value="{{ $user->id }}" />

                        
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="text" class="form-control form-control-lg @error('transaction_reference') is-invalid @enderror" placeholder="Prove of Payment eg. transaction slip code"
                                        name="transaction_reference" aria-label="text" value="{{ old('transaction_reference') }}">
                                    @error('transaction_reference')
                                    <div class="text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="text" class="form-control form-control-lg @error('payment_type') is-invalid @enderror" placeholder="Type of Payment eg. Online Transfer, Cash Payment etc .."
                                        name="payment_type" aria-label="text" value="{{ old('payment_type') }}">
                                    @error('payment_type')
                                    <div class="text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>


@endsection