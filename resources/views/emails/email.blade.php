@component('mail::message')
Dear {{ $user->name }},

Intruder DETECTED!
 
With best regards,<br>
The team {{ config('app.name') }}
 
@component('mail::button', ['url'=> config('app.url')])
Go to the platform!
@endcomponent
 
@endcomponent