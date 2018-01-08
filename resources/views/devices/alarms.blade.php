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

                        <a href="/alarm/new" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
								<i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
							Create  <span class="hidden-xs">new alarm</span>
						</a>
					</div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-striped table-condensed data-table">
								<thead>
									<tr>
										<th>Alarm Name</th>
                                        <th>Alarm Info</th>
                                        <th>Edit Alarm</th>
                                        <th>Delete Alarm</th>
									</tr>
								</thead>
								<tbody>
                                @foreach ($alarms as $a)
                                    <td> Alarm X </td>
                                    <td>Alarm information</td>
                                    <td>
                                        {!! Form::open(['method' => 'DELETE','route' => ['alarm.edit', $a->id],'style'=>'display:inline']) !!}
												{!! Form::submit('Edit', ['class' => 'btn btn-sm btn-warning']) !!}
										{!! Form::close() !!}
                                    </td>
                                    <td>
                                        {!! Form::open(['method' => 'DELETE','route' => ['alarm.delete', $a->id],'style'=>'display:inline']) !!}
												{!! Form::submit('Delete', ['class' => 'btn btn-sm btn-danger']) !!}
										{!! Form::close() !!}
                                    </td>
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

