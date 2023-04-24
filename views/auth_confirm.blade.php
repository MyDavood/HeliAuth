we received a request to log in on <code>{{ $url }}</code> with your Telegram account.

To authorize this request, use the <b>'Confirm'</b> button below, Or enter <code>{{ $code }}</code>.

<b>Username</b>: {{ $username }}
<b>Browser</b>: {{ $ua->ua->family }} {{ $ua->ua->major }} on {{ $ua->os->family }} {{ $ua->os->major }},{{ $ua->os->minor }}
<b>IP</b>: {{ $ip }}

If you didn't request this, use the 'Decline' button or ignore this message.
@if($status == 1)

✅ Accepted
@elseif($status === 2)

❌ Declined
@elseif($status === 3)

❌ Expired
@endif