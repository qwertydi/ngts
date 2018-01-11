@component('mail::message')
Dear {{ $user->name }},

It was detected movement on device with name [{{$device->name}}] and with the id #{{$device->id}}.

The date of the detected movement was at {{ $date }}.

And the video detected can be downloaded from the follow url:  <a href='{{$motion->url}}'>Download Link</a>.

With best regards,<br>
The NGTS {{ config('app.name') }} team.
 
@component('mail::button', ['url'=> config('app.url')])
Go to the platform!
@endcomponent
 
@endcomponent