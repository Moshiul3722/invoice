@component('mail::message')
    # Wecomes {{ $client->name }}

    The body of your message.

    @component('mail::panel')
        This is the panel content.
    @endcomponent


    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
