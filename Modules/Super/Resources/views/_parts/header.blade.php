
<div class="row">
	<div class="col-md-12">
		<header class="top-header">
			<ul class="navbar-nav ml-auto"> 

				<!-- Messages Dropdown Menu -->
				<li class="nav-item dropdown">
					<a class="nav-link" data-toggle="dropdown" href="#">
						<i class="fa fa-comments"></i>
						<span class="badge badge-danger navbar-badge">{{ $messages->count() }}</span>
					</a>
					<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
						<!-- Message Start -->
						<span class="dropdown-item dropdown-header">{{ $messages->count() }} Messages</span>
						@if(!$messages->isEmpty())
							@foreach($messages as $msg)
							<a href="#" class="dropdown-item">
								<div class="media">
									<img src="{{ asset('public/storage/avatar'.$msg->from->avatar) }}" alt="User Avatar" class="img-size-50 mr-3 img-circle">
									<div class="media-body">
										<h3 class="dropdown-item-title">
											{{ $msg->from->name }}
										</h3>
										<p class="text-sm">{{ $msg->message }}</p>
										<p class="text-sm text-muted"><i class="fa fa-clock-o mr-1"></i> {{ $msg->created_at->diffForHumans() }}</p>
									</div>
								</div>
							</a>
							<div class="dropdown-divider"></div>
							@endforeach
						@endif
						
						<a href="javascript:;" class="dropdown-item dropdown-footer">See All Messages</a>
					</div>
				</li>
				<!-- Notifications Dropdown Menu -->
				<li class="nav-item dropdown">
					<a class="nav-link" data-toggle="dropdown" href="#">
						<i class="fa fa-bell"></i>
						<span class="badge badge-warning navbar-badge">{{ $notifications->count() }}</span>
					</a>
					<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
						<span class="dropdown-item dropdown-header">{{ $notifications->count() }} Notifications</span>
						<div class="dropdown-divider"></div>
						@if(!$notifications->isEmpty())
							@foreach($notifications as $n)
							<a href="javascript:;" class="dropdown-item">
								<i class="fa fa-envelope mr-2"></i> {{ $n->data }}
								<span class="float-right text-muted text-sm">{{ $n->created_at->diffForHumans() }}</span>
							</a>
							<div class="dropdown-divider"></div>
							@endforeach
						@endif
						<a href="javascript:;" class="dropdown-item dropdown-footer">See All Notifications</a>
					</div>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link" data-toggle="dropdown" href="#"> 
						<i class="fa fa-user"></i>
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<a href="javascript:;" class="dropdown-item">Profile</a> 
						<div class="dropdown-divider"></div>
						<a href="{{ route('super.logout') }}" class="dropdown-item">Logout</a>
					</div>
				</li> 
			</ul>
		</header>
	</div>
</div>
