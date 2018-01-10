@component('mail::message')
Dear {{ $user->name }},

It was detected movement on device id: {{ $alarm_id }}.

The date of the detected movement was at {{ $date }}.
 
With best regards,<br>
The team {{ config('app.name') }}
 
@component('mail::button', ['url'=> config('app.url')])
Go to the platform!
@endcomponent
 
@endcomponent