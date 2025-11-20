<p>Dear {{ $booking->user->name }},</p>
<p>Your ADC booking is confirmed for {{ $booking->adcDate->date->toDateString() }} at {{ $booking->adcDate->centre->name }}.</p>
<p>Booking reference: {{ $booking->id }}</p>
