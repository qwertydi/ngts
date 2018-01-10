@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h1>Intelligent Surveillance System</h1></div>

                <div class="panel-body">
                    Create an account and setup your IoT devices to get information about intruders!
                    <div class="list-group">
                    <ul class="panel-body">
                        <button type="button" class="list-group-item list-group-item-action active">
                        In this website you are able to create:
                        </button>
                        <li type="button" class="list-group-item list-group-item-action">Add Devices</li>
                        <li type="button" class="list-group-item list-group-item-action">Monitoring Devices</li>
                        <li type="button" class="list-group-item list-group-item-action">Delete devices</li>
                        <li type="button" class="list-group-item list-group-item-action">Check Devices Streams - live</li>
                        <li type="button" class="list-group-item list-group-item-action">Check Surveillance history</li>
                        <li type="button" class="list-group-item list-group-item-action">Add alarms</li>
                        <li type="button" class="list-group-item list-group-item-action">Delete alarms</li>
                    </ul>
                    </div>
                    <div style="text-align: center">
                        <span class="badge badge-pill badge-primary">IoT</span>
                        <span class="badge badge-pill badge-primary">Any Time</span>
                        <span class="badge badge-pill badge-primary">Any Where</span>
                        <span class="badge badge-pill badge-primary">Any Thing</span>
                        <span class="badge badge-pill badge-primary">Video Stream</span>
                        <span class="badge badge-pill badge-primary">Private Cloud</span>
                        <span class="badge badge-pill badge-primary">Alarms</span>
                        <span class="badge badge-pill badge-primary">Surveillance</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection