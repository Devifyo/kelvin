<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #2c3a4a; background: #faf7f2; padding: 20px; }
        .card { max-width: 600px; margin: 0 auto; background: #ffffff; border: 1px solid #e8dfd2; border-top: 4px solid #b5722a; padding: 40px; }
        .header { font-size: 20px; font-weight: bold; color: #1a2332; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px; }
        .label { font-size: 12px; font-weight: bold; color: #b5722a; text-transform: uppercase; margin-bottom: 5px; }
        .value { font-size: 16px; margin-bottom: 20px; color: #2c3a4a; }
        .footer { font-size: 12px; color: #7a8fa0; margin-top: 30px; border-top: 1px solid #e8dfd2; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">New Website Inquiry</div>
        
        <div class="label">From</div>
        <div class="value">{{ $data['name'] }} ({{ $data['email'] }})</div>

        <div class="label">Subject</div>
        <div class="value">{{ $data['subject'] }}</div>

        <div class="label">Message</div>
        <div class="value" style="white-space: pre-wrap;">{{ $data['message'] }}</div>

        <div class="footer">
            This message was sent via the contact form on {{ parse_url(config('app.url'), PHP_URL_HOST) ?: config('app.url') }}
        </div>
    </div>
</body>
</html>