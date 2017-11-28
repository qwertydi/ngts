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
			<div class="col-md-10 col-md-offset-1">
				<div class="panel panel-default">
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
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($devices as $device)
									<tr>
									<td>{{$device->id}}</td>
									<td>{{$device->owner_id}}</td>
									<td>{{$device->name}}</td>
									<td>{{$device->ip_address}}</td>
									<td>{{$device->active}}</td>
									</tr>
								@endforeach
							</tbody>
						</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection