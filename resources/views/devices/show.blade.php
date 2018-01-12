@extends('layouts.app')

@section('template_title')
	{{ Auth::user()->name }}'s Devices
@endsection

@section('template_fastload_css')

	#map-canvas{
		min-height: 300px;
		height: 100%;
		width: 100%;
	}

@endsection

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-20 col-md-offset-0">
				<div class="panel panel-default">
				@if (count($devices)> 0)
					<div class="panel-heading">
						<h2>Devices available:</h2>
					</div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-striped table-condensed data-table">
								<thead>
									<tr class="success">
										<th>Device ID</th>
										<th>Owner ID</th>
										<th>Device Name</th>
										<th>IP Address</th>
										<th>MAC Address</th>
										<th>Status</th>
										<th>Surveillance</th>
										<th>Pictures</th>
										<th>Motion Log</th>
										<!--<th>Device Settings</th>-->
										<th>Delete Device</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($devices as $device)
										<tr>
											<td>{{$device->id}}</td>
											<td>{{$device->owner_id}}</td>
											<td>{{$device->name}}</td>
											<td><a href="http://{{$device->ip_address}}" id="linkid">{{$device->ip_address}}</a></td>
											<td>{{$device->mac_address}}</td>
											<td>
											@if ($device->active == 1 )
												<span class="label label-success">Active</span>
											@elseif ($device->active == 0)
												<span class="label label-danger">Disabled</span>
											@endif
											</td>
											<td>
											<a class="btn btn-sm btn-default " href="{{ URL::to('devices/' . $device->id . '/surveillance') }}" data-toggle="tooltip" title="Show"><span>Surveillance</span></a>
											</td>
											<td>
											<a class="btn btn-sm btn-default " href="{{ URL::to('devices/' . $device->id . '/pictures') }}" data-toggle="tooltip" title="Show"><span>Pictures</span></a>
											</td>
											<td>
												<a class="btn btn-sm btn-success " href="{{ URL::to('devices/' . $device->id) }}" data-toggle="tooltip" title="Show"><span>Motion History</span></a>
											</td>
											<!--<td>
											<a class="btn btn-sm btn-warning " href="{{ URL::to('devices/' . $device->id) }}" data-toggle="tooltip" title="Show"><span>Settings</span></a>
											
											</td>-->
											<td>
											{!! Form::open(['method' => 'DELETE','route' => ['delete.device', $device->id],'style'=>'display:inline']) !!}
												{!! Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm">Delete</span><span class="hidden-xs hidden-sm hidden-md"> device</span>', array('class' => 'btn btn-danger btn-sm','type' => 'button', 'style' =>'width: 100%;' ,'data-toggle' => 'modal', 'data-target' => '#confirmDelete', 'data-title' => 'Delete Device', 'data-message' => 'Are you sure you want to delete this Device ?')) !!}

											{!! Form::close() !!}
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
						</div>
					@else
					<div class="panel-heading">
						<h1>You don't have devices available</h1>
					</div>
					<div class="panel-body">
						<h3>Set up you raspberry or esp to send information!</h3>
					</div>
					@endif	
				</div>
			</div>
		</div>
	</div>
	@include('modals.modal-delete')

@endsection

@section('footer_scripts')

    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
    {{--
        @include('scripts.tooltips')
    --}}
@endsection

