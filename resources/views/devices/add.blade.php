@extends('layouts.app')

@section('template_title')
  Add new device
@endsection

@section('template_fastload_css')
@endsection

@section('content')

  <div class="container">
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
          <div class="panel-heading">

            Add new device

            <a href="/devices" class="btn btn-info btn-xs pull-right">
              <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
              Back <span class="hidden-xs">to</span><span class="hidden-xs"> Devices</span>
            </a>

          </div>
          <div class="panel-body">

            {!! Form::open(array('action' => 'DevicesController@store')) !!}

              <div class="form-group has-feedback row {{ $errors->has('name') ? ' has-error ' : '' }}">
                {!! Form::label('name', 'Name for device', array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::text('name', NULL, array('id' => 'name', 'class' => 'form-control', 'placeholder' => 'Name for device')) !!}
                    <label class="input-group-addon" for="name"><i class="fa fa-fw" aria-hidden="true"></i></label>
                  </div>
                  @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                  @endif
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('ip_address') ? ' has-error ' : '' }}">
                {!! Form::label('ip_address', 'ip_address', array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::text('ip_address', NULL, array('id' => 'ip_address', 'class' => 'form-control', 'placeholder' => 'ip_address')) !!}
                    <label class="input-group-addon" for="ip_address"><i class="fa fa-fw" aria-hidden="true"></i></label>
                  </div>
                  @if ($errors->has('ip_address'))
                    <span class="help-block">
                        <strong>{{ $errors->first('ip_address') }}</strong>
                    </span>
                  @endif
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('mac_address') ? ' has-error ' : '' }}">
                {!! Form::label('mac_address', 'mac_address', array('class' => 'col-md-3 control-label')); !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::text('mac_address', NULL, array('id' => 'mac_address', 'class' => 'form-control', 'placeholder' => 'mac_address')) !!}
                    <label class="input-group-addon" for="mac_address"><i class="fa fa-fw" aria-hidden="true"></i></label>
                  </div>
                  @if ($errors->has('mac_address'))
                    <span class="help-block">
                        <strong>{{ $errors->first('mac_address') }}</strong>
                    </span>
                  @endif
                </div>
              </div>

              <div class="form-group has-feedback row {{ $errors->has('role') ? ' has-error ' : '' }}">
                    {!! Form::label('active', 'Select status of device', array('class' => 'col-md-3 control-label')); !!}
                    <div class="col-md-9">
                    <div class="input-group">
                        <select class="form-control" name="active" id="active">
                        <option value="">Set status of device</option>
                            <option value="1">Active</option>
                            <option value="0">Disabled</option>
                        </select>
                        <label class="input-group-addon" for="active"><i class="fa fa-fw active }}" aria-hidden="true"></i></label>
                    </div>
                    @if ($errors->has('role'))
                        <span class="help-block">
                            <strong>{{ $errors->first('role') }}</strong>
                        </span>
                    @endif
                    </div>
                </div>

                <div class="form-group has-feedback row {{ $errors->has('role') ? ' has-error ' : '' }}">
                    {!! Form::label('type', 'Select type of device', array('class' => 'col-md-3 control-label')); !!}
                    <div class="col-md-9">
                    <div class="input-group">
                        <select class="form-control" name="type" id="type">
                        <option value="">Set type for device</option>
                            <option value="0">Raspberry Pi 3 Model B</option>
                            <option value="1">Raspberry Pi Zero [Any Model]</option>
                            <option value="2">ESP-[Any Model]</option>
                        </select>
                        <label class="input-group-addon" for="type"><i class="fa fa-fw type }}" aria-hidden="true"></i></label>
                    </div>
                    @if ($errors->has('role'))
                        <span class="help-block">
                            <strong>{{ $errors->first('role') }}</strong>
                        </span>
                    @endif
                    </div>
                </div>

              {!! Form::button('<i class="fa fa-user-plus" aria-hidden="true"></i>&nbsp;' . 'add device', array('class' => 'btn btn-success btn-flat margin-bottom-1 pull-right','type' => 'submit', 'method' => 'POST' )) !!}

            {!! Form::close() !!}

          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('footer_scripts')
@endsection
