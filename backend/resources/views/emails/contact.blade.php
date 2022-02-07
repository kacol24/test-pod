@component('mail::message')
# Offline Message From: {{ $data['name'] }}

Name: {{ $data['name'] }}<br/>
Email: {{ $data['email'] }}<br/>
Message: {{ $data['message'] }}
@endcomponent
