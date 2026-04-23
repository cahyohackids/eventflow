<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Helvetica', 'Arial', sans-serif; }
        body { background: #f9fafb; padding: 20px; }
        .ticket { background: white; border: 2px solid #7c3aed; border-radius: 16px; overflow: hidden; max-width: 600px; margin: 0 auto; }
        .header { background: linear-gradient(135deg, #7c3aed, #4f46e5); color: white; padding: 20px 24px; }
        .header h1 { font-size: 14px; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; opacity: 0.8; margin-bottom: 4px; }
        .header h2 { font-size: 22px; font-weight: 800; }
        .body { padding: 24px; }
        .qr-section { text-align: center; margin-bottom: 20px; }
        .qr-section img { width: 150px; height: 150px; }
        .ticket-code { font-family: monospace; font-size: 20px; font-weight: 700; color: #7c3aed; margin-top: 8px; letter-spacing: 2px; }
        .info-grid { display: table; width: 100%; margin-top: 16px; }
        .info-row { display: table-row; }
        .info-label { display: table-cell; padding: 6px 0; font-size: 11px; color: #6b7280; text-transform: uppercase; font-weight: 600; width: 100px; }
        .info-value { display: table-cell; padding: 6px 0; font-size: 13px; color: #111827; font-weight: 500; }
        .footer { border-top: 1px dashed #e5e7eb; padding: 16px 24px; text-align: center; font-size: 10px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="header">
            <h1>E-Ticket</h1>
            <h2>{{ $attendee->orderItem->order->event->title }}</h2>
        </div>
        <div class="body">
            <div class="qr-section">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($attendee->ticket_code) }}" alt="QR">
                <div class="ticket-code">{{ $attendee->ticket_code }}</div>
            </div>
            <div class="info-grid">
                <div class="info-row"><div class="info-label">Nama</div><div class="info-value">{{ $attendee->full_name }}</div></div>
                <div class="info-row"><div class="info-label">Tiket</div><div class="info-value">{{ $attendee->orderItem->ticketTier->name }}</div></div>
                <div class="info-row"><div class="info-label">Tanggal</div><div class="info-value">{{ $attendee->orderItem->order->event->start_at->format('d M Y, H:i') }}</div></div>
                <div class="info-row"><div class="info-label">Venue</div><div class="info-value">{{ $attendee->orderItem->order->event->venue_name ?? 'Online' }}</div></div>
                <div class="info-row"><div class="info-label">Order</div><div class="info-value">{{ $attendee->orderItem->order->order_code }}</div></div>
            </div>
        </div>
        <div class="footer">
            Tunjukkan QR code ini saat check-in · Eventify &copy; {{ date('Y') }}
        </div>
    </div>
</body>
</html>
