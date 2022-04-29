@extends('super::layouts.main')

@section('content')
<div class="main-content container">
    <div class="content">
        <div class="content-head">
            <div class="left-part">
                <h3>Recent Posts</h3>
            </div>
            <div class="right-part"></div>
        </div>
        <div class="content-body">
        @if(!$posts->isEmpty())
            <table class="table" id="posts">
                <thead>
                    <tr>
                        <th>Posted At</th>
                        <th>Posted By</th>
                        <th>Text Content</th>
                        <th>Media Files</th>
                        <th>Preview Files</th>
                        <th>Tips</th>
                        <th>Is Paid Media</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach( $posts as $p )
                    <tr>
                        <td>{{ $p->created_at->diffForHumans() }}</td>
                        <td>
                            {{ $p->author->name }}
                            (<a href="javascript:;" class="user-link">&#64;{{ $p->author->username }}</a>)
                        </td>
                        <td>{{ $p->text_content ? substr($p->text_content, 0, 50) : '---No Text Content---' }}</td>
                        <td>
                            {{ $p->medias->count() }}
                        </td>
                        <td>
                            {{ $p->previews->count() }}
                        </td>
                        <td>
                            {{ $p->total_tips() }}
                        </td>
                        <td>
                            @if($p->is_conditional)
                                Yes
                            @else
                                No
                            @endif
                        </td>
                        <td>
                            {{ $p->post_status }}
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('super.post.view', ['post' => $p->id]) }}" class="btn btn-sm-round text-info" title="View post" data-toggle="tooltip">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>
                                @if(in_array($p->status, [0, 2, 4]))
                                    <a class="btn btn-sm-round text-success" href="{{ route('super.post.publish', ['post' => $p->id]) }}" onclick="return confirm('Are you sure to publish this post?')" title="Publish post" data-toggle="tooltip">
                                        <i class="fa fa-check-circle" aria-hidden="true"></i>
                                    </a>
                                @endif

                                @if(in_array($p->status, [0, 1]))
                                    <a class="btn btn-sm-round text-warning" href="{{ route('super.post.archive.post', ['post' => $p->id]) }}" onclick="return confirm('Are you sure to archive this post?')" title="Archive post" data-toggle="tooltip">
                                        <i class="fa fa-archive" aria-hidden="true"></i>
                                    </a>
                                    <a class="btn btn-sm-round text-danger" href="{{ route('super.post.block.post', ['post' => $p->id]) }}" onclick="return confirm('Are you sure to block this post?')" title="Block post" data-toggle="tooltip">
                                        <i class="fa fa-ban" aria-hidden="true"></i>
                                    </a>
                                @endif

                                <a class="btn btn-sm-round text-danger" href="{{ route('super.post.delete', ['post' => $p->id]) }}" onclick="return confirm('Are you sure to delete this post?')" title="Delete post" data-toggle="tooltip"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
        @include('super::_parts.no-data', ['t'=>'No Recent Posts found!'])
        @endif
        </div>
    </div>
</div>
@endsection
@push('footer-script')
<script>
    $(function(){
        if($('#posts').length){
            $("#posts").dataTable({
                "bSort":false
            });
        }
    })
</script>
@endpush
