@extends('super::layouts.main')

@section('content')
<div class="main-content container">
    <div class="content">
        <div class="content-head">
            <div class="left-part">
                <h3>All Requests</h3>
            </div>
            <div class="right-part"></div>
        </div>
        <div class="content-body">
        @if(!$requests->isEmpty())
            <table class="table" id="requests">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Requested</th>
                        <th>Updated</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach( $requests as $r )
                    <tr>
                        <td>{{ $r->id }}</td>
                        <td>
                            {{ $r->user->name }}
                            (<a href="javascript:;" class="user-link">&#64;{{ $r->user->username }}</a>)
                        </td>
                        <td>{{ $r->user->email }}</td>
                        <td>
                            {{ $r->created_at->diffForHumans() }}
                        </td>
                        <td>
                            {{ $r->updated_at->diffForHumans() }}
                        </td>
                        <td>
                            @if($r->status === 0 )
                                <span class="btn btn-sm btn-dark">Pending</span>
                            @elseif($r->status === 1)
                                <span class="btn btn-sm btn-warning">Need Update</span>
                            @elseif($r->status === 3)
                                <span class="btn btn-sm btn-danger">Rejected</span>
                            @endif
                        </td>
                        <td>
                        <a href="{{ route('super.request', ['creatorRequest' => $r->id]) }}" class="view-icon-link">view</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
        @include('super::_parts.no-data', ['t'=>'No Requests Pending!'])
        @endif
        </div>
    </div>
</div>
@endsection
@push('footer-script')
<script>
    $(function(){
        if($('#requests').length){
            $("#requests").dataTable({
                "bSort":false
            });
        }
    })
</script>
    
@endpush
