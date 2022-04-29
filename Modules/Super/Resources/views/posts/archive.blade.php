@extends('super::layouts.main')

@section('content')
<div class="main-content container">
    <div class="content">
        <div class="content-head">
            <div class="left-part">
                <h3>Archived Posts</h3>
            </div>
            <div class="right-part"></div>
        </div>
        <div class="content-body">
        @if(!$posts->isEmpty())
            <table class="table dataTable">
                <thead>
                    <tr>
                        <th>Archived At</th>
                        <th>Posted By</th>
                        <th>Text Content</th>
                        <th>Media Files</th>
                        <th>Preview Files</th>
                        <th>Tips</th>
                        <th>Is Paid Media</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach( $posts as $p )
                    <tr>
                        <td>{{ $p->updated_at->diffForHumans() }}</td>
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
                            {{ $p->post_status }}
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('super.post.report.view',['post' => $p->id]) }}" class="btn btn-sm-round text-info">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>
                                <a class="btn btn-sm-round text-success" href="{{ route('super.post.publish', ['post' => $p->id]) }}" onclick="return confirm('Are you sure to publish this post?')" title="Publish post" data-toggle="tooltip">
                                    <i class="fa fa-check-circle" aria-hidden="true"></i>
                                </a>
                                <a class="btn btn-sm-round text-danger" href="{{ route('super.post.delete', ['post' => $p->id]) }}" onclick="return confirm('Are you sure to delete this post?')" title="Delete post" data-toggle="tooltip"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
        @include('super::_parts.no-data', ['t'=>'No Archived Posts found!'])
        @endif
        </div>
    </div>
</div>
@endsection
