@extends('layouts.app')

@section('template_title')
	VIDEO STREAM
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
                        </div>
                    </div>
                </div>
        </div>
    </div>
@endsection