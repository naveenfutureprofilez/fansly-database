@extends('super::layouts.main')

@section('content')
<div class="main-content container">
    <div class="content">
        <div class="content-head">
            <div class="left-part">
                <h3>Post</h3>
            </div>
            <div class="right-part">
                <a href="javascript:;" class="btn-sm-round text-danger" onclick="blockPost()" title="Block The Post">
                    <i class="fa fa-ban" aria-hidden="true"></i>
                </a>
                <a href="javascript:;" class="btn-sm-round text-success" onclick="clearReports()" title="Clear All Reports">
                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                </a>
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
                                        <video src="{{ $p->url }}"></video>
                                    @else
                                    <img src="{{ $p->url }}" alt="">
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
                                    <video src="{{ $m->url }}"></video>
                                @else
                                <img src="{{ $m->url }}" alt="">
                                @endif
                                </div>
                            @endif
                        @endforeach
                        </div>
                    @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <h3>Reports</h3>
                    @foreach ($post->reports as $report)
                    <div class="report-block">
                        <div class="user-block">
                            <div class="avatar">
                                <img src="{{ asset('public/storage/avatar/'.$report->reporter->avatar) }}" alt="">
                            </div>
                            <div class="user-info">
                                <h2>{{ $report->reporter->name }}</h2>
                                <span>{{ $report->reporter->username }}</span>
                            </div>
                        </div>
                        <h3>{{ $report->reason }}</h3>
                        <p>{{ $report->explains }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('footer-script')
    <script>
        const cUrl = "{{ route('super.post.report.clear',['post' => $post->id]) }}";
        const bUrl = "{{ route('super.post.block.post',['post' => $post->id]) }}";

        function clearReports(){
            if(confirm('Are you sure to clear all reports?')){
                window.location.href = cUrl;
            }
        }

        function blockPost(){
            if(confirm('Are you sure to block this?')){
                window.location.href = bUrl;
            }
        }
    </script>
@endpush
