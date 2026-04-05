<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Lead Inquiry</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111827; line-height: 1.5;">
    <h2 style="margin-bottom: 8px;">New Lead Inquiry Received</h2>
    <p style="margin-top: 0; color: #6b7280;">A new inquiry was submitted through the contact form.</p>

    <table cellpadding="8" cellspacing="0" border="0" style="border-collapse: collapse; width: 100%; max-width: 640px;">
        <tr><td><strong>Name</strong></td><td>{{ $leadInquiry->full_name }}</td></tr>
        <tr><td><strong>Email</strong></td><td>{{ $leadInquiry->email }}</td></tr>
        <tr><td><strong>Phone</strong></td><td>{{ $leadInquiry->phone ?: '-' }}</td></tr>
        <tr><td><strong>Company</strong></td><td>{{ $leadInquiry->company ?: '-' }}</td></tr>
        <tr><td><strong>Service Interest</strong></td><td>{{ $leadInquiry->service_interest ?: '-' }}</td></tr>
        <tr><td><strong>Budget</strong></td><td>{{ $leadInquiry->budget ?: '-' }}</td></tr>
        <tr><td><strong>Timeline</strong></td><td>{{ $leadInquiry->project_timeline ?: '-' }}</td></tr>
        <tr><td><strong>Stage</strong></td><td>{{ $leadInquiry->stage }}</td></tr>
        <tr><td><strong>Submitted</strong></td><td>{{ $leadInquiry->created_at->toDateTimeString() }}</td></tr>
    </table>

    <h3 style="margin-top: 16px;">Message</h3>
    <p>{{ $leadInquiry->message }}</p>
</body>
</html>
