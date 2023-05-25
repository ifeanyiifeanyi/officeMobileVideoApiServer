@extends('admin.layouts/adminlayout')


@section('title', 'View and Manage Comment')
@section('adminlayout')

<div class="container-fluid py-4" style="height:100vh">

    <div class="row mt-4">
        <div class="col-lg-12 mb-lg-0 mb-4">
            <div class="card ">
                <div class="card-header pb-0 p-3">
                    @if(session('status'))
                    <div class="alert alert-success">{{session('status')}}</div>
                    @endif

                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h2 class="mb-2">@yield('title')</h2>

                    </div>
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-borderd">

                                <tr>
                                    <th>Comment</th>
                                    <td>
                                        {!! $comment->comment !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>User </th>
                                    <td>
                                        {{ $comment->user->username }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Blog Title </th>
                                    <td>
                                        {{ $comment->blog->title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <p> {{ $comment->status == 0 ? "Pending" : "Approved" }}</p>
                                        <p>
                                            <form>
                                                <div class="form-group">
                                                    <select class="form-control">
                                                        <option>Select Comment Status</option>
                                                        @if ($comment->status == 0)
                                                        <option value="1">Approve Comment</option>
                                                        @else
                                                        <option value="0">Suspend Account</option>
                                                        @endif
                                                    </select>
                                                    <button class="btn btn-info mt-2">Submit</button>
                                                </div>
                                            </form>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Reply Comment: </th>
                                    <td>
                                        <form>
                                            <div class="form-group">
                                                <textarea class="form-control"></textarea>
                                            </div>
                                            <button class="btn btn-sm btn-in">Send Comment</button>
                                    </td>
                                </tr>

                            </table>

                        </div>

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
<script>
    $(function() {
        $(document).on('click', '#delete', function(e) {
            e.preventDefault();
            var link = $(this).data("id");
            console.log({
                link
            });

            Swal.fire({
                title: 'Are you sure?'
                , text: "You won't be able to revert this!"
                , icon: 'warning'
                , showCancelButton: true
                , confirmButtonColor: '#3085d6'
                , cancelButtonColor: '#d33'
                , confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    if ($("#delete").submit()) {
                        Swal.fire(
                            'Deleted!'
                            , 'Your file has been deleted.'
                            , 'success'
                        )
                    }
                }
            })
        })

    })

</script>



@endsection
