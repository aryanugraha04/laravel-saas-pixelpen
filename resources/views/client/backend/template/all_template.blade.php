@extends('client.client_dashboard')
@section('client')

<div class="nk-content-inner">
    <div class="nk-content-body">
        <div class="nk-block-head nk-page-head">
            <div class="nk-block-head-between flex-wrap gap g-2">
                <div class="nk-block-head-content">
                    <h2 class="display-6">Template Library</h2>
                </div>
                <div class="nk-block-head-content">
                    <div class="d-flex gap gx-4">
                        <div class="">
                            <ul class="d-flex gap gx-2">
                                <li>
                                    <a href="templates-list.html" class="btn btn-md btn-icon btn-outline-light"><em class="icon ni ni-view-list-wd"></em></a>
                                </li>
                                <li>
                                    <a href="templates.html" class="btn btn-md btn-icon btn-primary btn-soft"><em class="icon ni ni-grid-fill"></em></a>
                                </li>
                            </ul>
                        </div>
                        <div class="">
                            <div class="form-control-wrap">
                                <div class="form-control-icon start md text-light">
                                    <em class="icon ni ni-search"></em>
                                </div>
                                <input type="text" class="form-control form-control-md" placeholder="Search Template">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- .nk-page-head -->
        <div class="nk-block">
            
            <div class="row g-gs filter-container" data-animation="true">
                @foreach ($templates as $item)
                <div class="col-sm-6 col-xxl-3 filter-item blog-content">
                    <div class="card card-full shadow-none h-100">
                        <div class="card-body d-flex flex-column"> {{-- Tambahkan class d-flex --}}

                            <div class="media media-rg media-middle media-circle text-primary bg-primary bg-opacity-20 mb-3">
                                <em class="{{ $item->icon }}"></em>
                            </div>

                            <h5 class="fs-4 fw-medium">{{ $item->title }}</h5>
                            <p class="small text-light line-clamp-2">{{ $item->description }}</p>
                            
                            {{-- TOMBOL AKSI BARU DI BAWAH --}}
                            <div class="mt-auto d-flex gap-2"> {{-- mt-auto akan mendorong tombol ke bawah --}}
                                <a href="{{ route('user.details.template', $item->id) }}" class="btn btn-sm btn-primary w-100">
                                    <em class="fa-solid fa-pencil me-1"></em>
                                    <span>Details</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div><!-- .row -->
        </div><!-- .nk-block -->
    </div>
</div>

@endsection