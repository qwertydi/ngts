@extends('layouts.app')

@section('template_title')
  Motion History by Device
@endsection

@php
  $levelAmount = trans('usersmanagement.labelUserLevel');
  if ($user->level() >= 2) {
      $levelAmount = trans('usersmanagement.labelUserLevels');
  }
@endphp

@section('content')

  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="panel @if ($user->activated == 1) panel-default @else panel-danger @endif">
          <div class="panel-heading">
						<strong> Motion History on device with ID:</strong>  {{ $id }}

							<a href="/devices/" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
								<i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
							Back  <span class="hidden-xs">to Devices</span>
							</a>

          </div>
					@if(count($motion)>0)
						<div class="panel-body">

							<div class="clearfix"></div>
							<div class="border-bottom"></div>

			
							<table class="table table-striped table-condensed data-table">
								<thead>
								<tr class="success">
									<th>Device ID</th>
									<th>Date</th>
									<th>Video URL</th>
									<th>Delete Motion Info</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($motion as $m)
										<tr>
											<td>{{$m->device_id}}</td>
											<td>{{ Carbon\Carbon::parse($m->date)->format('d-m-Y H:m') }}</td>
											<td><a href="{{$m->url}">Video link</a>{{$m->url}</td>
											<td>
											{!! Form::open(['method' => 'DELETE','route' => ['delete.motion', $m->device_id,$m->id],'style'=>'display:inline']) !!}
												{!! Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm">Delete</span><span class="hidden-xs hidden-sm hidden-md"> Device</span>', array('class' => 'btn btn-danger btn-sm','type' => 'button', 'style' =>'width: 100%;' ,'data-toggle' => 'modal', 'data-target' => '#confirmDelete', 'data-title' => 'Delete Motion', 'data-message' => 'Are you sure you want to delete this Motion history ?')) !!}
											{!! Form::close() !!}
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
					</div>
					@else
					<div class="panel-body">
						<h3>No motion information</h3>
					</div>
					@endif
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