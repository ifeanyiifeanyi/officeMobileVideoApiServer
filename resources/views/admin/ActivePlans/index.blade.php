@extends('admin.layouts/adminlayout')


@section('title', 'Active Payment Plan Details')
@section('adminlayout')

<div class="container-fluid py-4" style="height:100vh">

    <div class="row mt-4">
        <div class="col-lg-12 mb-lg-0 mb-4">
            <div class="card shadow-lg">
                <div class="card-header pb-0 p-3">
                    @if(session('status'))
                    <div class="alert alert-success">{{session('status')}}</div>
                    @endif
                    <div class="d-flex justify-content-between">

                        <h4 class="mb-2">@yield('title')</h4>
                        <p style="float: right"><a class="btn btn-outline-primary" href="{{ route("payment.plan") }}"><i class="fas fa-plus"></i>
                                Back to plans</a></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 table-responsive">
                        <table class="table table-bordered table-hover">

                            <tr>
                                <th>#</th>
                                <th>Plan</th>
                                <th>Amount</th>
                                <th>Duration</th>
                                <th>Transaction Reference</th>
                                <th>User</th>
                                <th>User Email</th>
                                <th>Status</th>
                                <th>View</view>
                            </tr>
                           @if($activePlans->count())
                                @foreach($activePlans as $activePlan)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$activePlan->payment_name}}</td>
                                        <td>{{$activePlan->amount}}</td>
                                        <td>{{$activePlan->duration_in_name}}</td>
                                        <td>{{$activePlan->transaction_reference}}</td>
                                        <td>{{$activePlan->username}}</td>
                                        <td>{{$activePlan->email}}</td>
                                        <td>{{$activePlan->status == 1? "Active" : "Not active"}}</td>
                                        <td><a href="{{ route('user.subscribe.plan', $activePlan->activePlanId) }}" class="btn btn-primary btn-sm">View</a></td>

                                    </tr>

                                @endforeach
                           @else
                                <h2>No Active Subscription(s)</h2>
                           @endif
                        </table>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


@endsection
@section('scripts')
<script src="{{ asset('backend/assets/js/core/jquery.js') }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


@endsection
