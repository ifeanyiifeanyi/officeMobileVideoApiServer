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
                        <div class="col-md-12">
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
                                                <form method="post" action="{{ route('comment.update.status') }}">
                                                    @csrf
                                                    <input type="hidden" name="comment_id" value={{$comment->id}} />
                                                    <div class="form-group">
                                                        <select class="form-control" name="status">
                                                            <option>Select Comment Status</option>
                                                            @if ($comment->status == 0)
                                                            <option value="1">Approve Comment</option>
                                                            @else
                                                            <option value="0">Suspend Comment</option>
                                                            @endif
                                                        </select>
                                                        @error('status')
                                        <div class="text text-danger">{{ $message }}</div>
                                        @enderror
                                                    
                                                    </div>
                                                    <button class="btn btn-info mt-2">Submit</button>
                                                </form>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Reply Comment: </th>
                                        <td>
                                            <form method="post" action="{{route('comment.reply.message')}}">
                                            @csrf
                                            <input type="hidden" name="comment_id" value={{$comment->id}} />
                                                <div class="form-group">
                                                    <textarea class="form-control" name="reply"></textarea>
                                                    @error('reply')
                                        <div class="text text-danger">{{ $message }}</div>
                                        @enderror
                                                </div>
                                                <button class="btn btn-sm btn-info">Submit Reply</button>
                                        </td>
                                    </tr>

                                </table>

                            </div>
                        </div>
                        <div class="col-md-12">
                            <h2>Our replies to the comment</h2>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    @if($replies->count())
                                    @foreach($replies as $reply)
                                        <tr>
                                            <th>{{$loop->iteration}}</th>
                                            <th>{!! $reply->reply !!}</th>
                                            <th>{{ \Carbon\Carbon::parse($reply->created_at)->diffForHumans()}}</th>
                                        </tr>
                                    @endforeach
                                    @else
                                        <h4>No replies </h4>
                                    @endif
                                </table>
                            </div>
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
