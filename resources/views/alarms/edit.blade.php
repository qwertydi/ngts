@extends('layouts.app')

@section('template_title')
  Add new alarm
@endsection

@section('template_fastload_css')
@endsection

@section('content')

  <div class="container">
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
          <div class="panel-heading">

            Add new alarm

            <a href="/alarms" class="btn btn-info btn-xs pull-right">
              <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
              Back <span class="hidden-xs">to</span><span class="hidden-xs"> Alarms</span>
            </a>

          </div>
          @if(count($devices)>0)
            <div class="panel-body">

                {!! Form::model($alarm, array('action' => array('DevicesController@updateUserAccount', $alarm->id), 'method' => 'PUT')) !!}

                {!! csrf_field() !!}

                <div class="form-group has-feedback row {{ $errors->has('role') ? ' has-error ' : '' }}">
                    {!! Form::label('device_id', 'Select device for alarm', array('class' => 'col-md-3 control-label')); !!}
                    <div class="col-md-9">
                    <div class="input-group">
                        <select class="form-control" name="device_id" id="device_id">
                        <option value="">Set device for alarm</option>
                        @if (count($devices)>0)
                            @foreach($devices as $d)
                            <option value="{{ $d->id }}">Device ID: {{ $d->id }} | Device MAC: {{ $d->mac_address }}</option>
                            @endforeach
                        @endif
                        </select>
                        <label class="input-group-addon" for="name"><i class="fa fa-fw device }}" aria-hidden="true"></i></label>
                    </div>
                    @if ($errors->has('role'))
                        <span class="help-block">
                            <strong>{{ $errors->first('role') }}</strong>
                        </span>
                    @endif
                    </div>
                </div>

                <div class="form-group has-feedback row {{ $errors->has('role') ? ' has-error ' : '' }}">
                    {!! Form::label('type', 'Select type for alarm', array('class' => 'col-md-3 control-label')); !!}
                    <div class="col-md-9">
                    <div class="input-group">
                        <select class="form-control" name="type" id="type">
                        <option value="">Set type for alarm</option>
                            <option value="0">Time Interval</option>
                            <option value="1">Always Active</option>
                            <option value="2">Disabled</option>
                        </select>
                        <label class="input-group-addon" for="name"><i class="fa fa-fw type }}" aria-hidden="true"></i></label>
                    </div>
                    @if ($errors->has('role'))
                        <span class="help-block">
                            <strong>{{ $errors->first('role') }}</strong>
                        </span>
                    @endif
                    </div>
                </div>

                <div class="form-group has-feedback row {{ $errors->has('role') ? ' has-error ' : '' }}">
                    {!! Form::label('start_hour', 'Select start hour for alarm', array('class' => 'col-md-3 control-label')); !!}
                    <div class="col-md-9">
                    <div class="input-group">
                        {!! Form::text('start_hour', old('start_hour'), array('id' => 'start_hour', 'class' => 'form-control', 'placeholder' => 'Start Hour - HH:mm')) !!}
                        <label class="input-group-addon" for="name"><i class="fa fa-fw fa-pencil }}" aria-hidden="true"></i></label>
                    </div>
                    @if ($errors->has('role'))
                        <span class="help-block">
                            <strong>{{ $errors->first('role') }}</strong>
                        </span>
                    @endif
                    </div>
                </div>

                <div class="form-group has-feedback row {{ $errors->has('role') ? ' has-error ' : '' }}">
                    {!! Form::label('end_hour', 'Select end hour for alarm', array('class' => 'col-md-3 control-label')); !!}
                    <div class="col-md-9">
                    <div class="input-group">
                        {!! Form::text('end_hour', old('end_hour'), array('id' => 'end_hour', 'class' => 'form-control', 'placeholder' => 'End Hour - HH:mm')) !!}
                        <label class="input-group-addon" for="name"><i class="fa fa-fw fa-pencil }}" aria-hidden="true"></i></label>
                    </div>
                    @if ($errors->has('role'))
                        <span class="help-block">
                            <strong>{{ $errors->first('role') }}</strong>
                        </span>
                    @endif
                    </div>
                </div>

                

                <div class="col-xs-6">
                    {!! Form::button('<i class="fa fa-fw fa-save" aria-hidden="true"></i> Save Changes', array('class' => 'btn btn-success btn-block margin-bottom-1 btn-save','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#confirmSave', 'data-title' => 'Edit', 'data-message' => 'Do you want to apply the changes?')) !!}
                 </div>

                {!! Form::close() !!}

            </div>
          @else
            <div class="panel-body">
                <h3>No devices available!</h3>
                <h3>You are not able to add alarms</h3>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  @include('modals.modal-save')
  @include('modals.modal-delete')

@endsection

@section('footer_scripts')

  @include('scripts.delete-modal-script')
  @include('scripts.save-modal-script')
  @include('scripts.check-changed')

@endsection
