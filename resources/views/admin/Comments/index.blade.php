@extends('admin.layouts/adminlayout')


@section('title', 'Comment Management')
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
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Post Title</th>
                                        <th>Comment By (user)</th>
                                        <th>Status</th>
                                        <th>View</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                               <tbody>
                                @foreach($comments as $comment)

                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $comment->blog->title }}</td>
                                    <td>{{ $comment->user->username }}</td>
                                    <td>{{ $comment->status == 0 ? 'Pending' : 'Approved' }}</td>
                                    <td>
                                        <a href="{{ route('comment.view', $comment->id) }}" class="btn btn-sm btn-info">View</a>
                                    </td>
                                    <td>
                                        <a href="" class="btn btn-sm btn-danger">Delete</a>
                                    </td>


                                </tr>

                                @endforeach
                               </tbody>
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
