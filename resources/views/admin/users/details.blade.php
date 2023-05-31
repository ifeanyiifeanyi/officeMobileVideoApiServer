@extends('admin.layouts/adminlayout')


@section('title', 'User :: ' . $user->username)
@section('adminlayout')
    <style>
        .section {
            padding: 100px 0;
            position: relative;
        }

        .gray-bg {
            background-color: #f5f5f5;
        }

        img {
            max-width: 100%;
        }

        img {
            vertical-align: middle;
            border-style: none;
        }

        /* About Me
        ---------------------*/
        .about-text h3 {
            font-size: 45px;
            font-weight: 700;
            margin: 0 0 6px;
        }

        @media (max-width: 767px) {
            .about-text h3 {
                font-size: 35px;
            }
        }

        .about-text h6 {
            font-weight: 600;
            margin-bottom: 15px;
        }

        @media (max-width: 767px) {
            .about-text h6 {
                font-size: 18px;
            }
        }

        .about-text p {
            font-size: 18px;
            max-width: 450px;
        }

        .about-text p mark {
            font-weight: 600;
            color: #20247b;
        }

        .about-list {
            padding-top: 10px;
        }

        .about-list .media {
            padding: 5px 0;
        }

        .about-list label {
            color: #20247b;
            font-weight: 600;
            width: 88px;
            margin: 0;
            position: relative;
        }

        .about-list label:after {
            content: "";
            position: absolute;
            top: 0;
            bottom: 0;
            right: 11px;
            width: 1px;
            height: 12px;
            background: #20247b;
            -moz-transform: rotate(15deg);
            -o-transform: rotate(15deg);
            -ms-transform: rotate(15deg);
            -webkit-transform: rotate(15deg);
            transform: rotate(15deg);
            margin: auto;
            opacity: 0.5;
        }

        .about-list p {
            margin: 0;
            font-size: 15px;
        }

        @media (max-width: 991px) {
            .about-avatar {
                margin-top: 30px;
            }
        }

        .about-section .counter {
            padding: 22px 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 30px rgba(31, 45, 61, 0.125);
        }

        .about-section .counter .count-data {
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .about-section .counter .count {
            font-weight: 700;
            color: #20247b;
            margin: 0 0 5px;
        }

        .about-section .counter p {
            font-weight: 600;
            margin: 0;
        }

        mark {
            background-image: linear-gradient(rgba(252, 83, 86, 0.6), rgba(252, 83, 86, 0.6));
            background-size: 100% 3px;
            background-repeat: no-repeat;
            background-position: 0 bottom;
            background-color: transparent;
            padding: 0;
            color: currentColor;
        }

        .theme-color {
            color: #fc5356;
        }

        .dark-color {
            color: #20247b;
        }
    </style>
    <div class="container-fluid py-4" style="height:100vh">

        <section class="section about-section gray-bg" id="about">
            <div class="container">
                <div class="row align-items-center flex-row-reverse">
                    <div class="col-lg-6">
                        <div class="about-text go-to">
                            <h3 class="dark-color">{{ $user->name }}</h3>

                            <div class="row about-list">
                                <div class="col-md-6 col-sm-6">
                                    <div class="media">
                                        <label>Date Join</label>
                                        <p>{{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }}</p>
                                    </div>
                                    <div class="media">
                                        <label>Account Code</label>
                                        <p>{{ $user->userid }}</p>
                                    </div>
                                    <div class="media">
                                        <label>Username</label>
                                        <p>{{ $user->username }}</p>
                                    </div>


                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <div class="media">
                                        <label>E-mail</label>
                                        <p>{{ $user->email }}</p>
                                    </div>

                                    <div class="media">
                                        <label>Account Type </label>
                                        <p> {{ $user->role_as === 1 ? 'Admin' : 'Subscriber' }}</p>
                                    </div>
                                    <div class="media">
                                        <label>Account Status</label>
                                        <p>{{ $user->status == 1 ? 'Verified' : 'Not Verified' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="about-avatar">
                            <img width="100%"
                                src="{{ $user->image
                                    ? $user->image
                                    : "
                                                                                        https://ui-avatars.com/api/?name=" .
                                        $user->name .
                                        '&background=0D8ABC&color=fff&bold=true&size=128' }}"
                                title="" alt="">
                        </div>
                    </div>
                </div>
                <div class="counter">
                    @if ($activePlan != null)
                        <div class="row">
                            <div class="col-6 col-lg-3">
                                <div class="count-data text-center">
                                    <h6 class="count" data-to="500" data-speed="500">{{ $activePlan->amount }}</h6>
                                    <p class="m-0px font-w-600">Amount</p>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3">
                                <div class="count-data text-center">
                                    <h6 class="count" data-to="150" data-speed="150">{{ $paymentPlan->duration_in_name }}
                                    </h6>
                                    <p class="m-0px font-w-600">Duration</p>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3">
                                <div class="count-data text-center">
                                    <h6 class="count" data-to="850" data-speed="850">{{ $activePlan->payment_type }}</h6>
                                    <p class="m-0px font-w-600">Payment Type</p>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3">
                                <div class="count-data text-center">
                                    <h6 class="count" data-to="190" data-speed="190">{{ $paymentPlan->name }}</h6>
                                    <p class="m-0px font-w-600">Subscription Package</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <h4>No Active Subscription</h4>
                        <hr>
                        <h5 class="text-primary">Select Subscription Package for user</h5>
                        <div class="row">
                        @foreach($allplans as $allplan)

                        <div class="col-lg-4 col-md-12">
                        <div class="card bg-gradient-info mb-2">
                            <div class="card-header bg-gradient-info">
                                <h4 style="color:white !important">  {{ $allplan->name }}  </h4>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table">
                                    <tr>
                                        <th>Amount</th>
                                        <td><pre>{{ $allplan->amount }}</pre></td>
                                    </tr>
                                    <tr>
                                        <th>Duration</th>
                                        <td><pre>{{ $allplan->duration_in_name }}</pre></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('subscription.select', ['userId' => $user->id, 'subscriptionId' => $allplan->id]) }}" class="btn btn w-100 bg-gradient-light">Select Subscription Plan</a>
                            </div>

                        </div>
                        </div>
                        @endforeach

                        </div>

                    @endif
                </div>
            </div>
        </section>

    </div>


@endsection
@section('scripts')
    <script src="{{ asset('backend/assets/js/core/jquery.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(function() {
            $(document).on('click', '#delete', function(e) {
                e.preventDefault();
                var link = $(this).data("id");
                console.log({
                    link
                });

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        if ($("#delete").submit()) {
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                        }
                    }
                })
            })

        })
    </script>



@endsection
