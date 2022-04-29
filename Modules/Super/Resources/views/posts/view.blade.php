@extends('super::layouts.main')

@section('content')
<div class="main-content container">
    <div class="content">
        <div class="content-head">
            <div class="left-part">
                <h3>Post</h3>
            </div>
            <div class="right-part">
                @if(in_array($post->status, [0, 2, 4]))
                    <a class="btn btn-sm-round text-success" href="{{ route('super.post.publish', ['post' => $post->id]) }}" onclick="return confirm('Are you sure to publish this post?')" title="Publish post" data-toggle="tooltip">
                        <i class="fa fa-check-circle" aria-hidden="true"></i>
                    </a>
                @endif

                @if(in_array($post->status, [0, 1]))
                    <a class="btn btn-sm-round text-warning" href="{{ route('super.post.archive.post', ['post' => $post->id]) }}" onclick="return confirm('Are you sure to archive this post?')" title="Archive post" data-toggle="tooltip">
                        <i class="fa fa-archive" aria-hidden="true"></i>
                    </a>
                    <a class="btn btn-sm-round text-danger" href="{{ route('super.post.block.post', ['post' => $post->id]) }}" onclick="return confirm('Are you sure to block this post?')" title="Block post" data-toggle="tooltip">
                        <i class="fa fa-ban" aria-hidden="true"></i>
                    </a>
                @endif

            </div>
        </div>
        <div class="content-body">
            <div class="row post-view">
                <div class="col-md-6">
                    <div class="post-head">
                        <div class="avatar">
                            <img src="{{ asset('public/storage/avatar/'.$post->author->avatar) }}" alt="">
                        </div>
                        <div class="user-info">
                            <h2>{{ $post->author->name }}</h2>
                            <span>{{ $post->author->username }}</span>
                        </div>
                    </div>
                    <div class="post-content">
                        <p>{{ $post->text_content }}</p>
                    </div>
                    <div class="post-media">
                    @if(!$post->previews->isEmpty())
                        <h3>Previes</h3>
                        <div class="media-content">
                            @foreach ($post->previews as $p)
                                @if($p->url)
                                    <div class="media-block">
                                    @if($p->type == 'video')
                                        <video src="{{ asset('public/storage/post/media/'.$p->full_name) }}"></video>
                                    @else
                                        <img src="{{ asset('public/storage/post/media/'.$p->full_name) }}" alt="">
                                    @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                    @if(!$post->medias->isEmpty())
                        <h3>Media</h3>
                        <div class="media-content">
                        @foreach ($post->medias as $m)
                            @if($m->url)
                                <div class="media-block">
                                @if($m->type == 'video')
                                    <video src="{{ asset('public/storage/post/media/'.$m->full_name) }}"></video>
                                @else
                                <img src="{{ asset('public/storage/post/media/'.$m->full_name) }}" alt="">
                                @endif
                                </div>
                            @endif
                        @endforeach
                        </div>
                    @endif
                    </div>
                </div>
                <div class="col-md-6">
                    @if(!$payments->isEmpty())
                        <h3>Payments</h3>
                        @foreach ($payments as $p)
                        <div class="report-block">
                            <div class="user-block">
                                <div class="avatar">
                                    <img src="{{ asset('public/storage/avatar/'.$p->commenter->avatar) }}" alt="">
                                </div>
                                <div class="user-info">
                                    <h2>{{ $p->commenter->name }}</h2>
                                    <span>{{ $p->commenter->username }}</span>
                                </div>
                            </div>
                            <h3>£{{ $p->amount }} - {{ $p->created_at->diffForHumans() }}</h3>
                        </div>
                        @endforeach
                    @endif
                    @if(!$comments->isEmpty())
                        <h3>Comments</h3>
                        @foreach ($comments as $c)
                        <div class="report-block">
                            <div class="user-block">
                                <div class="avatar">
                                    <img src="{{ asset('public/storage/avatar/'.$c->commenter->avatar) }}" alt="">
                                </div>
                                <div class="user-info">
                                    <h2>{{ $c->commenter->name }}</h2>
                                    <span>{{ $c->commenter->username }}</span>
                                </div>
                            </div>
                            <p>{{ $c->comment }}</p>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('footer-script')
   
@endpush
