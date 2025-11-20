<!doctype html>
<html><head><meta charset="utf-8"><title>User Home</title></head>
<body>
    <h1>Welcome, {{ $user->name }}</h1>
    <p>Level: {{ $user->level->code ?? 'N/A' }}</p>
    <h2>Your Booking</h2>
    @if($booking)
        <p>Booking ID: {{ $booking->id }}</p>
        <p>Date: {{ $booking->adcDate->date->toDateString() }} at {{ $booking->adcDate->centre->name }}</p>
        <p>Status: {{ $booking->status }}</p>
    @else
        <p>You have no booking. <a href="{{ route('bookings.create') }}">Book now</a></p>
    @endif
    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Logout</button></form>
</body>
</html>
