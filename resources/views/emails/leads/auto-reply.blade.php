<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thanks for contacting {{ $siteName }}</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111827; line-height: 1.6;">
    <h2 style="margin-bottom: 8px;">Thanks for reaching out, {{ $leadInquiry->full_name }}.</h2>
    <p style="margin-top: 0; color: #4b5563;">
        We received your inquiry and our team will get back to you shortly with the next steps.
    </p>

    <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 14px; max-width: 640px;">
        <p style="margin: 0 0 8px;"><strong>What you shared:</strong></p>
        <p style="margin: 0;"><strong>Service:</strong> {{ $leadInquiry->service_interest ?: 'Not specified' }}</p>
        <p style="margin: 0;"><strong>Timeline:</strong> {{ $leadInquiry->project_timeline ?: 'Not specified' }}</p>
    </div>

    <p style="margin-top: 16px;">
        If you have any updates before we reply, just respond to this email.
    </p>

    @if(filled($contactEmail))
        <p style="margin-top: 0; color: #4b5563;">
            Direct contact: <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>
        </p>
    @endif

    <p style="margin-top: 20px;">Regards,<br>{{ $siteName }} Team</p>
</body>
</html>
