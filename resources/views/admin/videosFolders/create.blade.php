@extends('admin.layouts/adminlayout')

@php
// phpinfo();
@endphp
@section('title', 'Create Video')
@section('mystyles')
<style>
    .fa-cog,
    .l {
        font-size: 35px !important;
        color: #fff !important;
    }
</style>
@endsection
@section('adminlayout')

<div class="container-fluid py-4" style="height:100vh">

    <div class="row mt-4">
        <div class="col-lg-12 mb-lg-0 mb-4">
            <div class="card ">
                <div class="card-header pb-0 p-3">
                    <div class="d-flex justify-content-between">

                        <h2 class="mb-2">Create Video</h2>
                        <p style="float: right"><a class="btn btn-outline-primary" href="{{ route('videos') }}"><i
                                    class="fas fa-arrow-left"></i> Videos</a></p>
                    </div>
                </div>
                <div class="card-body">
                    <form role="form" method="POST" action="{{ route('store.videos') }}" id="add_videos" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                <label>Video Title </label>
                                    <input type="text"
                                        class="form-control form-control-lg @error('title') is-invalid @enderror"
                                        placeholder="Title" name="title" aria-label="text" value="{{ old('title') }}" autofocus>
                                    @error('title')
                                    <div class="text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                <label>Select Video Category </label>
                                    <select
                                        class="form-control form-control-lg @error('category_id') is-invalid @enderror"
                                        name="category_id">
                                        <option>Select Category</option>
                                        @if(count($categories) > 0)

                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach

                                        @else
                                        <option value="">Not Available</option>
                                        @endif
                                    </select>
                                    @error('category_id')
                                    <div class="text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                <label>Duration of Video In Number </label>

                                    <input type="text"
                                        class="form-control form-control-lg @error('length') is-invalid @enderror"
                                        placeholder="Duration In Number" name="length" aria-label="text"
                                        value="{{ old('length') }}">
                                    @error('length')
                                    <div class="text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                <label>Select Video Genre </label>

                                    <select class="form-control form-control-lg @error('genre_id') is-invalid @enderror"
                                        placeholder="Genre" name="genre_id">
                                        <option value="">Video Genre</option>
                                        @if ($genre)
                                        @foreach ($genre as $genre_value)
                                        <option value="{{ $genre_value->id }}">{{ ucwords($genre_value->name) }}
                                        </option>

                                        @endforeach
                                        @else
                                        <option>No genre available</option>
                                        @endif
                                    </select>
                                    @error('genre_id')
                                    <div class="text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                <label>Select Video Rating </label>

                                    <select
                                        class="form-control form-control-lg @error('rating_id') is-invalid @enderror"
                                        placeholder="Rating" name="rating_id">
                                        <option value="">Select Video Rating</option>
                                        @if ($ratings)
                                        @foreach ($ratings as $rating)
                                        <option value="{{ $rating->id }}">{{ $rating->name }}</option>
                                        @endforeach
                                        @else
                                        <option value="">No Rating Available</option>
                                        @endif
                                    </select>
                                    @error('rating_id')
                                    <div class="text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                <label>Select Video Parental Control </label>
                                    <select
                                        class="form-control form-control-lg @error('parent_control_id') is-invalid @enderror"
                                        placeholder="Parential Control" name="parent_control_id">
                                        <option value="">Parental Control</option>
                                        @if ($parentControls)
                                        @foreach ($parentControls as $pc)
                                        <option value="{{ $pc->id }}">{{ $pc->name }}</option>
                                        @endforeach
                                        @else
                                        <option value="">No parental Control</option>
                                        @endif
                                    </select>
                                    @error('parent_control_id')
                                    <div class="text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                <label>Upload Video</label>
                                    <label for="">Video</label>
                                    <input type="file"
                                        class="form-control form-control-lg @error('video') is-invalid @enderror"
                                        placeholder="Video File" name="video" aria-label="text"
                                        value="{{ old('video') }}"
                                        onchange="showPreviewVideo(event)"
                                        >
                                    @error('video')
                                    <div class="text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Selected video </label><br>
                                <video id="video-preview" src="" controls class="responsive w-50" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="">Upload Thumbnail</label>
                                    <input type="file"
                                        class="form-control form-control-lg @error('thumbnail') is-invalid @enderror"
                                        placeholder="Thumbnail" name="thumbnail" aria-label="file"
                                        onchange="showPreviewThumbnail(event)"
                                        value="{{ old('thumbnail') }}">
                                    @error('thumbnail')
                                    <div class="text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Selected video Thumbnail</label><br>
                                <img id="img-preview" style="width:200px" src="{{asset('backend/assets/img/problem.png')}}" alt="" class="img img-fluid" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                <label>Short Video Description</label>

                                    <textarea name="short_description"
                                        class="form-control form-control-lg @error('short_description') is-invalid @enderror"
                                        placeholder="Short Description"></textarea>
                                    @error('short_description')
                                    <div class="text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                <label>Long Video Description </label>

                                    <textarea name="long_description"
                                        class="form-control form-control-lg @error('long_description') is-invalid @enderror"
                                        placeholder="Long Description"></textarea>
                                    @error('long_description')
                                    <div class="text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <div class="col-md-4">
                                        <div class="mb-3 form-check form-switch">
                                            <input @error('status') is-invalid @enderror class="form-check-input"
                                                type="checkbox" id="flexSwitchCheckDefault" name="status"> 
                                                <label
                                                class="form-check-label"
                                                for="flexSwitchCheckDefault"><b>Set Video Status</b></label>
                                            @error('status')
                                            <div class="text text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="text-center">

                            <button id="btn1" type="submit" class="btn btn-lg bg-gradient-info  w-100 mt-4 mb-0">Save
                            </button>


                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>


@endsection
@section('videoScripts')
<script>
function showPreviewThumbnail(event){
    if(event.target.files.length > 0){
        let src = URL.createObjectURL(event.target.files[0]);
        let preview = document.getElementById('img-preview');
        preview.src = src;
    }
}
function showPreviewVideo(event){
    if(event.target.files.length > 0){
        let src = URL.createObjectURL(event.target.files[0]);
        let preview = document.getElementById('video-preview');
        preview.src = src;
    }
}

</script>

<script src="{{ asset('backend/assets/js/core/jquery.js') }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    $("#add_videos").submit(function(e){
        e.preventDefault();
        const fd = new FormData(this);

        $.ajax({
            url: "{{ route('store.videos') }}",
            method: "POST",
            data: fd,
            cache:false,
            processData: false,
            contentType:false,
            beforeSend: function () {
                    $('#btn1').html('<i class="fas fa-cog fa-spin"></i> <span class="l">Loading</span>');
                    $('#btn1').attr("disabled", true);
            },
            
            success: function(res){
                console.log(res);
                let data = res.error;
                if (data) {    
                    $('#btn1').html('Save');
                    $('#btn1').attr("disabled", false);
                    $.each(data, function( index, value ) {
                        toastr.error(value);
                    });      
                    return false; 
                }
                if (res.success) {
                    $('#add_videos').trigger("reset");
                    $('#btn1').html('Save');
                    $('#btn1').attr("disabled", false);
                    Swal.fire(
                        'Created',
                        'Content upload was successful',
                        'success'
                    );
                    setTimeout(function () {
                        $('#add_videos').trigger("reset");
                        $('#btn1').html('Save');
                        $('#btn1').attr("disabled", false);
                        window.location.href="{{ route('videos') }}";
                    }, 5000);
                }
               
            },

        })
        // .fail(function(xhr, status, error) {
        //     console.log("me me me me", error);
           
        //     Swal.fire({
        //         icon: 'error',
        //         title: 'Oops...',
        //         text: xhr.statusText
        //     });
        //     // setTimeout(function(){
        //     //     location.reload();
        //     // },3000);
        // });
    })
</script>



@endsection