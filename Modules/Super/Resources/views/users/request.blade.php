@extends('super::layouts.main')

@section('content')
<div class="main-content container">
    <div class="content">
        <div class="content-head d-flex flex-wrap justify-content-between align-items-center">
            <div class="left-part">
                <h3>Creator verification Request</h3>
            </div>
            <div class="right-part">
                <a href="javascript:;" class="btn-sm-round text-dark" onclick="markIncomplete()">
                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                </a>
                <a href="javascript:;" class="btn-sm-round text-success" onclick="approveRequest()">
                    <i class="fa fa-check-circle" aria-hidden="true"></i>
                </a>
                <a href="javascript:;" class="btn-sm-round text-danger" onclick="rejectRequest()">
                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                </a>
            </div>
        </div>
        <div class="content-body">
			<div class="row data-forms">
				<div class="col-md-6 col-sm-12">
					<div class="box-block">
						<h4>{{ $request->user->name .'('.$request->user->username.')' }}</h4>
						<div class="form-group">
                            <label for="street">Address</label>
                            {{ Form::text('street', $request->address['street'] ,['class' => 'form-control', 'readonly'=>true]) }}
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <label for="city">City</label>
                                    {{ Form::text('city', $request->address['city'] ,['class' => 'form-control', 'readonly'=>true]) }}
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="city">State</label>
                                    {{ Form::text('state', $request->address['state'] ,['class' => 'form-control', 'readonly'=>true]) }}
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="country">Country</label>
                                    {{ Form::text('country', $request->address['country'] ,['class' => 'form-control', 'readonly'=>true]) }}
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="zip">Zip Code</label>
                                    {{ Form::text('zip', $request->address['zip'] ,['class' => 'form-control', 'readonly'=>true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            @if($request->social)
                                @foreach($request->social as $i => $s)
                                @php
                                    if(strpos($s,'http', 0) === false){
                                        $s = 'https://'.$s;
                                    }
                                @endphp
                                <div class="social-link">
                                    <p>
                                        {{ $i }} - <a href="{{ $s }}" class="social-link">Visit</a>
                                    </p>
                                </div>
                                @endforeach
                            @endif
                        </div>
					</div>
				</div>
				<div class="col-md-6 col-sm-12">
                    <div class="box-block">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <label for="id_type">ID Type</label>
                                    {{ Form::text('id_type', $request->id_type ,['class' => 'form-control', 'readonly'=>true]) }}
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="id_type">ID Expiry</label>
                                    {{ Form::text('id_expiry', $request->id_expire == 0 ? $request->id_expiry : 'Not expire' ,['class' => 'form-control', 'readonly'=>true]) }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="id_no">ID No.</label>
                            {{ Form::text('id_no', $request->id_no ,['class' => 'form-control', 'readonly'=>true]) }}
                        </div>
                        <div class="form-group v-img-box">
                            <img src="{{ asset('public/storage/verify/'.$request->verify_img) }}" alt="verification-image" class="verify-image" />
                        </div>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade wyi-modal" id="modifyModal" tabindex="-1" role="dialog" aria-labelledby="modifyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content wyi-modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modifyModalLabel">Need Updates?</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            {{ Form::open(['id'=>'req-edit', 'url' => route('super.request.incomplete',['creatorRequest' => $request->id])]) }}
            <div class="form-group">
                {{ Form::textarea('remark', '', ['id' => 'remark', 'rows'=>5, 'class'=>'form-control', 'required' => true, 'placeholder' => 'Please write what information needs to update by the requester']) }}
            </div>
            <div class="form-group">
                <button class="wyi-submit float-right" type="submit">Update Request</button>
            </div>
            {{ Form::close() }}
        </div>
      </div>
    </div>
</div>
<div class="d-none">
    {{ Form::open(['id' => 'req-action', 'method'=> 'post']) }}
    {{ Form::close() }}
</div>
    
@endsection

@push('footer-script')
<script>
    const r = '{{ $request->id }}';
    const tA = '{{ route("super.approve", ["creatorRequest" => $request->id]) }}';
    const tR = '{{ route("super.reject", ["creatorRequest" => $request->id]) }}';
    const actionForm = $('form#req-action');
    const updateModal = $('div#modifyModal');
    function approveRequest(){
        if(confirm('Are you sure to approve verification of this user?')){
            actionForm.attr('action', tA);
            actionForm.submit();
        } else {
            return false;
        }
    }
    function rejectRequest(){
        if(confirm('Are you sure to reject verification of this user?')){
            actionForm.attr('action', tR);
            actionForm.submit();
        } else {
            return false;
        }
    }
    function markIncomplete(){
        updateModal.modal('show');
    }
</script>
@endpush