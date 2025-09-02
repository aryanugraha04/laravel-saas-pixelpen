@extends('client.client_dashboard')
@section('client')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="nk-content-inner">
    <div class="nk-content-body">
        <div class="nk-block-head nk-page-head">
            <div class="nk-block-head-between">
                <div class="nk-block-head-content">
                    <h2 class="display-6">Change Password</h2>
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
                    <form action="{{ route('user.password.update') }}" method="POST">
                        @csrf
                        {{-- Hapus enctype karena tidak ada upload file --}}
                        
                        {{-- Gunakan div tanpa class 'row' agar tidak menyamping --}}
                        <div> 
                            {{-- 1. New Password --}}
                            <div class="form-group mb-3"> {{-- Gunakan mb-3 untuk memberi jarak bawah --}}
                                <label for="new_password" class="form-label">New Password</label>
                                <div class="form-control-wrap">
                                    <input type="password" name="new_password" id="new_password" 
                                        class="form-control @error('new_password') is-invalid @enderror" placeholder="New Password">
                                    @error('new_password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- 2. Confirm Password --}}
                            <div class="form-group mb-3">
                                <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                <div class="form-control-wrap">
                                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" 
                                        class="form-control" placeholder="Confirm New Password">
                                </div>
                            </div>

                            {{-- 3. Old Password --}}
                            <div class="form-group mb-3">
                                <label for="old_password" class="form-label">Old Password</label>
                                <div class="form-control-wrap">
                                    <input type="password" name="old_password" id="old_password" 
                                        class="form-control @error('old_password') is-invalid @enderror" placeholder="Old Password">
                                    @error('old_password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            {{-- Tombol Save --}}
                            <div class="form-group mt-4">
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
