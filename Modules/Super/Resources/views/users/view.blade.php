@extends('super::layouts.main')

@section('content')
<div class="main-content container">
    <div class="content">
        <div class="content-head">
            <div class="left-part">
                <h3>{{ $user->role_type }}</h3>
            </div>
            <div class="right-part">
                

            </div>
        </div>
        <div class="content-body">
            <div class="row post-view">
                <div class="col-md-6">
                    <div class="post-head">
                        <div class="avatar">
                            <img src="{{ asset('public/storage/avatar/'.$user->avatar) }}" alt="">
                        </div>
                        <div class="user-info">
                            <h2>{{ $user->name }}</h2>
                            <span>{{ $user->username }}</span>
                        </div>
                    </div>
                    <div class="post-media">
                    @if($user->role == 1)
                        @if($user->approved)

                            <strong>Address</strong>
                            <p>{{ $user->approved->address['street'] }}, {{ $user->approved->address['city'] }}, {{ $user->approved->address['state'] }}, {{ $user->approved->address['country'] }} {{ $user->approved->address['zip'] }}</p>

                            <strong>Identity Document</strong>
                            <p>{{ $user->approved->id_type }} - {{ $user->approved->id_no }}</p>

                            <strong>ID Expiry</strong>
                            <p>
                                @if($user->approved->id_expire)
                                    {{ $user->approved->id_expiry->format('d-m-Y') }}
                                @else
                                No Expiry
                                @endif
                            </p>
                            <strong>Verification Image</strong>
                            <div class="form-group v-img-box">
                                <img src="{{ asset('public/storage/verify/'.$user->approved->verify_img) }}" alt="verification-image" class="verify-image" style="max-height: 250px"/>
                            </div>

                        @endif
                    @endif
                    </div>
                </div>
                <div class="col-md-6">
                    @if($user->role == 1)
                    <strong>Likes - {{ $user->likes($user->id) }}</strong><br/>
                    <strong>Followers - {{ $user->followers($user->id) }}</strong><br/>
                    <strong>Images - {{ $user->totalImages($user->id) }}</strong><br/>
                    <strong>Videos - {{ $user->totalVideos($user->id) }}</strong><br/>
                    <strong>Post - {{ $user->total_posts }}</strong><br/>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('footer-script')
   
@endpush
