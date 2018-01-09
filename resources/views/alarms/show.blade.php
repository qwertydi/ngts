@extends('layouts.app')

@section('template_title')
	Alarm's Manager
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
					<div class="panel-heading">
						<strong>Manage user alarms!</strong>

                        <a href="/alarms/add" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
								<i class="fa fa-fw fa-plus" aria-hidden="true"></i>
							Create  <span class="hidden-xs">new alarm</span>
						</a>
					</div>
					@if(count($alarms)>0)
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-striped table-condensed data-table">
								<thead>
									<tr>
										<th>Alarm ID</th>
                                        <th>Parent Device (ID)</th>
                                        <th>Type</th>
										<th>Time Interval</th>
										<th>Edit Alarm</th>
                                        <th>Delete Alarm</th>
									</tr>
								</thead>
								<tbody>
                                @foreach($alarms as $a)
									<tr>
										<td>{{$a->id}}</td>
										<td> <a href="/devices/{{$a->device_id}}">{{$a->device_id}}</td>
										<td>
										@switch( $a->type )
											@case( 0 )
												<span class="label label-success">Active During Time Interval</span>
											@break
											@case( 1 )
												<span class="label label-warning">Always Active</span>
											@break
											@case( 2 )
												<span class="label label-danger">Disabled</span>
											@break
											@default
												<span class="label label-info">Undefined</span>
											@break
										@endswitch
										</td>
										<td>
										@if($a->type == 0 )
										<span class="label label-info">{{$a->start_hour}}</span>
										<span class="label label-info">{{$a->end_hour}}</span>
										@else
											<span class="label label-warning">Not Defined</span>
										@endif
										</td>
										<td>
											<a href="/alarm/edit" class="btn btn-sm btn-default">Edit Alarm</a>
										</td>
										<td>
											{!! Form::open(['method' => 'DELETE','route' => ['alarms.delete', $a->id],'style'=>'display:inline']) !!}
												{!! Form::submit('Delete', ['class' => 'btn btn-sm btn-danger']) !!}
											{!! Form::close() !!}
										</td>
									</tr>
                                @endforeach
								</tbody>
							</table>
						</div>
					</div>
					@else 
					<div class="panel-body">
					<h3>You dont have alarms available!</h3>
					</div>
					@endif
				</div>
			</div>
		</div>
	</div>
@endsection

