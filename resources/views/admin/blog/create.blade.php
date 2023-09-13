@extends('admin.layouts/adminlayout')


@section('title', 'Create Blog')
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
                        <a href="{{ route('blog') }}" class="btn bg-gradient-primary text-right">All Blog</a>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <form action="{{ route('blog.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="title">Blog Title</label>
                                                <input type="text" name="title" id="title" class="form-control" placeholder="Blog Title" value="{{ old('title') }}" />
                                                @error('title')
                                                <span class="text-sm text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="author">Author</label>
                                                <input type="text" name="author" id="author" class="form-control" placeholder="Author" value="{{ old('author') }}" />
                                                @error('author')
                                                <span class="text-sm text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="thumbnail">Thumbnail</label>
                                                <input accept="image/*" onchange="showPreview(event)" type="file" name="thumbnail" id="thumbnail" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <img id="img-preview" style="width:200px" src="{{asset('backend/assets/img/problem.png')}}" alt="" class="img img-fluid" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="description">Blog Content</label>
                                            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                                            @error('description')
                                                <span class="text-sm text-danger">{{ $message }}</span>
                                                
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn bg-gradient-primary w-100">Save</button>
                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>
</div>


@endsection
@section('scripts')
<script>
function showPreview(event){
    if(event.target.files.length > 0){
        let src = URL.createObjectURL(event.target.files[0]);
        let preview = document.getElementById('img-preview');
        preview.src = src;
    }
}

</script>
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
