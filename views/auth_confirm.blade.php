We received a request to log in on <code>{{ $url }}</code> with your Telegram account.

To authorize this request, Enter <code>{{ $code }}</code>.

<b>Username</b>: {{ $username }}
<b>Browser</b>: {{ $browser }}
<b>IP</b>: {{ $ip }}

If you didn't request this, ignore this message.
@if($status === 1)

✅ Accepted
@elseif($status === 2)

❌ Declined
@elseif($status === 3)

❌ Expired
@endif