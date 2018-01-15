@extends('layouts.app')

@section('template_title')
	Device {{ $id }} surveillance links
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
						<strong>Stream available on device {{$id}}:</strong>

                     	<a href="/devices/" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
								<i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
							Back  <span class="hidden-xs">to Devices List</span>
						</a>

                        <a href="/devices/{{$id}}" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
								<i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
							Back  <span class="hidden-xs">to Device {{$id}}</span>
						</a>
						@if ($type == 0 || $type == 1)
						<a href="/devices/{{$id}}/surveillance/picture" class="btn btn-warning btn-xs pull-right" style="margin-left: 1em;">
								<i class="fa fa-fw fa-camera" aria-hidden="true"></i>
							Capture  <span class="hidden-xs">image on device {{$id}}</span>
						</a>
						@endif
					</div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-striped table-condensed data-table">
								<thead>
									<tr>
										<th>Live video stream</th>
									</tr>
								</thead>
								<tbody>
                                    <td>
                                    <div class="media">
										<div class="col col-lg-2">
										</div>
                                        <div class="col col-lg-10">
                                            <iframe width="640" height="480" src="{{$stream}}" frameborder="1" allowfullscreen>
                                            </iframe>
                                        </div>
										<div class="col col-lg-2">
									</div>
                                    </div>
                                    </td>
								</tbody>
							</table>
						</div>
						</div>
				</div>
			</div>
		</div>
	</div>
@endsection

