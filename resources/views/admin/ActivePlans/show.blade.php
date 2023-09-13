@extends('admin.layouts/adminlayout')


@section('title', 'User Subscription '.$user->name)
@section('adminlayout')

    <div class="container-fluid py-4" style="height:100vh">

        <div class="row mt-4">
            <div class="col-lg-12 mb-lg-0 mb-4">
                <div class="card shadow-lg">
                    <div class="card-header pb-0 p-3">
                        @if (session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                        @endif
                        <div class="d-flex justify-content-between">

                            <h4 class="mb-2">@yield('title')</h4>
                            <p style="float: right"><a class="btn btn-outline-primary" href="{{ route('payment.plan') }}"><i
                                        class="fas fa-arrow-left"></i>
                                    Back</a></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-bordered table-hover">
                                <tr>
                                    <th>Payment Type</th>
                                    <td>{{$activePlan->payment_type}}</td>
                                </tr>
                                <tr>
                                    <th>Subscription Plan</th>
                                    <td>{{$paymentPlan->name}}</td>
                                </tr>
                                <tr>
                                    <th>Duration</th>
                                    <td>{{$paymentPlan->duration_in_name}}</td>
                                </tr>
                                <tr>
                                    <th>User</th>
                                    <td>{{$user->name}}</td>
                                </tr>
                                <tr>
                                    <th>User Email</th>
                                    <td> {{$user->email}}</td>
                                </tr>
                                <tr>
                                    <th>Username</th>
                                    <td> {{$user->username}}</td>
                                </tr>
                                <tr>
                                    <th>Start Date: </th>
                                    <td>{{ ucwords(\Carbon\Carbon::parse($startDate)->diffForHumans()) }}</td>
                                </tr>
                                <tr>
                                    <th>End Date: </th>
                                    <td>{{ ucwords(\Carbon\Carbon::parse($endDate)->diffForHumans()) }}</td>
                                </tr>
                                <tr>
                                    <th>Remaining No. of Days: </th>
                                    <td>{{ $remainingDays }}</td>
                                </tr>


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
