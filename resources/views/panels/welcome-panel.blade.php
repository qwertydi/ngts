@php

    $levelAmount = 'level';

    if (Auth::User()->level() >= 2) {
        $levelAmount = 'levels';

    }

@endphp


<div class="panel panel-primary @role('admin', true) panel-info  @endrole">
    <div class="panel-heading">

        Welcome {{ Auth::user()->name }}

        @role('admin', true)
            <span class="pull-right label label-primary" style="margin-top:4px">
            Admin Access
            </span>
        @else
            <span class="pull-right label label-warning" style="margin-top:4px">
            User Access
            </span>
        @endrole

    </div>
    <div class="panel-body">
        <h2 class="lead">
            {{ trans('auth.loggedIn') }}
        </h2>

        <p>
            By registering it was created an <code>owncloud</code> for you. To login on owncloud you use your email and password!
        </p>

        <hr>

        <h4>
            You have
                @role('admin')
                   Admin
                @endrole
                @role('user')
                   User
                @endrole
            Access
        </h4>

        <hr>

        <h4>
            You have access to {{ $levelAmount }}:
            @level(5)
                <span class="label label-primary margin-half">5</span>
            @endlevel

            @level(4)
                <span class="label label-info margin-half">4</span>
            @endlevel

            @level(3)
                <span class="label label-success margin-half">3</span>
            @endlevel

            @level(2)
                <span class="label label-warning margin-half">2</span>
            @endlevel

            @level(1)
                <span class="label label-default margin-half">1</span>
            @endlevel
        </h4>

        @role('admin')

            <hr>

            <h4>
                You have permissions:
                @permission('view.users')
                    <span class="label label-primary margin-half margin-left-0"">
                        {{ trans('permsandroles.permissionView') }}
                    </span>
                @endpermission

                @permission('create.users')
                    <span class="label label-info margin-half margin-left-0"">
                        {{ trans('permsandroles.permissionCreate') }}
                    </span>
                @endpermission

                @permission('edit.users')
                    <span class="label label-warning margin-half margin-left-0"">
                        {{ trans('permsandroles.permissionEdit') }}
                    </span>
                @endpermission

                @permission('delete.users')
                    <span class="label label-danger margin-half margin-left-0"">
                        {{ trans('permsandroles.permissionDelete') }}
                    </span>
                @endpermission

            </h4>

        @endrole

    </div>
</div>