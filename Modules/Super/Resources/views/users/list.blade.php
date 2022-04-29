@extends('super::layouts.main')

@section('content')
<div class="main-content container">
    <div class="content">
        <div class="content-head">
            <div class="left-part">
                <h3>{{ $title }}</h3>
            </div>
            <div class="right-part"></div>
        </div>
        <div class="content-body">
        @if(!$users->isEmpty())
            <table class="table dataTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Followers</th>
                        <th>Likes</th>
                        {{-- <th>Type</th> --}}
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach( $users as $u )
                    <tr>
                        <td>{{ $u->id }}</td>
                        <td>
                            {{ $u->name }}
                            (<a href="javascript:;" class="user-link">&#64;{{ $u->username }}</a>)
                        </td>
                        <td>{{ $u->email }}</td>
                        <td>
                            {{ $u->followers() }}
                        </td>
                        <td>
                            {{ $u->likes() }}
                        </td>
                        <td>{{ $u->created_at->diffForHumans() }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('super.users.view', ['user' => $u->id]) }}" class="btn btn-sm-round text-info" title="View user" data-toggle="tooltip" target="_blank">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>
                                <a href="javascript:;" class="btn btn-sm-round text-primary" onclick="error('Under development!');" title="Edit" data-toggle="tooltip">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a>
                                <a href="javascript:;" class="btn btn-sm-round text-danger" onclick="error('Under development!');" title="Block user" data-toggle="tooltip">
                                    <i class="fa fa-ban" aria-hidden="true"></i>
                                </a>
                                <a href="javascript:;" class="btn btn-sm-round text-danger" onclick="error('Under development!');" title="Delete user" data-toggle="tooltip">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
        @include('super::_parts.no-data', ['t'=>'No '.$title.' found!'])
        @endif
        </div>
    </div>
</div>
@endsection
