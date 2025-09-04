@extends('admin.dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="nk-content-inner">
    <div class="nk-content-body">
        <div class="nk-block-head nk-page-head">
            <div class="nk-block-head-between">
                <div class="nk-block-head-content">
                    <h2 class="display-6">Update Slider</h2>
                </div>
            </div>
        </div><!-- .nk-page-head -->
        <div class="nk-block">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-head-content">
                </div>
            </div><!-- .nk-block-head -->
            <div class="card shadown-none">
                <div class="card-body">
                    <form action="{{ route('update.slider') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" id="" value="{{ $slider->id }}">
                        <div class="row g-3 gx-gs">
                            {{-- Slider Title (Satu Kolom Penuh) --}}
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="slider_title" class="form-label">Slider Title</label>
                                    <div class="form-control-wrap">
                                        <input type="text" name="title" id="slider_title" class="form-control" value="{{ $slider->title }}">
                                    </div>
                                </div>
                            </div>

                            {{-- Description (Satu Kolom Penuh & Menggunakan Textarea) --}}
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="slider_description" class="form-label">Description</label>
                                    <div class="form-control-wrap">
                                        <textarea name="description" id="slider_description" class="form-control" rows="4">{{ $slider->description }}</textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- Link (Satu Kolom Penuh) --}}
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="slider_link" class="form-label">Link</label>
                                    <div class="form-control-wrap">
                                        <input type="text" name="link" id="slider_link" class="form-control" value="{{ $slider->link }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-xl-12">
                                <button type="submit" class="btn btn-secondary">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div><!-- .card-body -->
            </div><!-- .card -->
        </div><!-- .nk-block -->
    </div>
</div>


@endsection
