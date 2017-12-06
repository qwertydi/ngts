@extends('layouts.app')

@section('template_title')
  Showing Device ID
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
            Device with ID: {{ $id }}
          </div>
          <div class="panel-body">

            <div class="clearfix"></div>
            <div class="border-bottom"></div>

     
            <table class="table table-striped table-condensed data-table">
			    <thead>
					<tr class="success">
						<th>Device ID</th>
						<th>Owner ID</th>
						<th>Device Name</th>
						<th>IP Address</th>
						<th>Status</th>
						<th>Device Log</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($device as $d)
							<tr>
        					<td>asd</td>
							<td>asd</td>
							<td>asd</td>
							<td>asd</td>
							<td>asd</td>
							<td><a class="btn btn-sm btn-success " href="#" data-toggle="tooltip" title="Show"><span>Show History</span>   </a></td>
    						</tr>
						@endforeach
					</tbody>
			    </table>
        </div>
      </div>
    </div>
  </div>

  @include('modals.modal-delete')

@endsection

@section('footer_scripts')

  @include('scripts.delete-modal-script')

@endsection