@extends('emails.layouts.brand-shell')

@section('content')
    <p style="margin: 0 0 16px; color: #374151; font-size: 14px; line-height: 1.6;">
        A new inquiry just arrived through your contact form. Review the details below and follow up quickly while the lead is warm.
    </p>

    <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-collapse: collapse; border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; margin-bottom: 18px;">
        <tr>
            <td style="padding:10px 12px; background:#f9fafb; border-bottom:1px solid #e5e7eb; width: 36%; font-size:13px; color:#4b5563;"><strong>Name</strong></td>
            <td style="padding:10px 12px; border-bottom:1px solid #e5e7eb; font-size:13px; color:#111827;">{{ $leadInquiry->full_name }}</td>
        </tr>
        <tr>
            <td style="padding:10px 12px; background:#f9fafb; border-bottom:1px solid #e5e7eb; font-size:13px; color:#4b5563;"><strong>Email</strong></td>
            <td style="padding:10px 12px; border-bottom:1px solid #e5e7eb; font-size:13px; color:#111827;">{{ $leadInquiry->email }}</td>
        </tr>
        <tr>
            <td style="padding:10px 12px; background:#f9fafb; border-bottom:1px solid #e5e7eb; font-size:13px; color:#4b5563;"><strong>Phone</strong></td>
            <td style="padding:10px 12px; border-bottom:1px solid #e5e7eb; font-size:13px; color:#111827;">{{ $leadInquiry->phone ?: '-' }}</td>
        </tr>
        <tr>
            <td style="padding:10px 12px; background:#f9fafb; border-bottom:1px solid #e5e7eb; font-size:13px; color:#4b5563;"><strong>Company</strong></td>
            <td style="padding:10px 12px; border-bottom:1px solid #e5e7eb; font-size:13px; color:#111827;">{{ $leadInquiry->company ?: '-' }}</td>
        </tr>
        <tr>
            <td style="padding:10px 12px; background:#f9fafb; border-bottom:1px solid #e5e7eb; font-size:13px; color:#4b5563;"><strong>Service Interest</strong></td>
            <td style="padding:10px 12px; border-bottom:1px solid #e5e7eb; font-size:13px; color:#111827;">{{ $leadInquiry->service_interest ?: '-' }}</td>
        </tr>
        <tr>
            <td style="padding:10px 12px; background:#f9fafb; border-bottom:1px solid #e5e7eb; font-size:13px; color:#4b5563;"><strong>Budget</strong></td>
            <td style="padding:10px 12px; border-bottom:1px solid #e5e7eb; font-size:13px; color:#111827;">{{ $leadInquiry->budget ?: '-' }}</td>
        </tr>
        <tr>
            <td style="padding:10px 12px; background:#f9fafb; border-bottom:1px solid #e5e7eb; font-size:13px; color:#4b5563;"><strong>Timeline</strong></td>
            <td style="padding:10px 12px; border-bottom:1px solid #e5e7eb; font-size:13px; color:#111827;">{{ $leadInquiry->project_timeline ?: '-' }}</td>
        </tr>
        <tr>
            <td style="padding:10px 12px; background:#f9fafb; border-bottom:1px solid #e5e7eb; font-size:13px; color:#4b5563;"><strong>Stage</strong></td>
            <td style="padding:10px 12px; border-bottom:1px solid #e5e7eb; font-size:13px; color:#111827; text-transform: capitalize;">{{ $leadInquiry->stage }}</td>
        </tr>
        <tr>
            <td style="padding:10px 12px; background:#f9fafb; font-size:13px; color:#4b5563;"><strong>Submitted</strong></td>
            <td style="padding:10px 12px; font-size:13px; color:#111827;">{{ $leadInquiry->created_at->toDateTimeString() }}</td>
        </tr>
    </table>

    <div style="border:1px solid #e5e7eb; border-radius:10px; padding:14px; background:#fafafa; margin-bottom:18px;">
        <p style="margin:0 0 8px; font-size:13px; color:#4b5563;"><strong>Project Brief</strong></p>
        <p style="margin:0; font-size:14px; color:#111827; line-height:1.6;">{{ $leadInquiry->message }}</p>
    </div>

    <table cellpadding="0" cellspacing="0" border="0" role="presentation">
        <tr>
            @if(! empty($adminLeadUrl))
                <td style="padding-right: 10px;">
                    <a href="{{ $adminLeadUrl }}" style="display:inline-block; padding:10px 16px; border-radius:8px; background:#111827; color:#ffffff; text-decoration:none; font-size:13px; font-weight:600;">
                        Open Lead Inbox
                    </a>
                </td>
            @endif
            <td>
                <a href="mailto:{{ $leadInquiry->email }}" style="display:inline-block; padding:10px 16px; border-radius:8px; background:#ffffff; border:1px solid #d1d5db; color:#111827; text-decoration:none; font-size:13px; font-weight:600;">
                    Reply to Lead
                </a>
            </td>
        </tr>
    </table>
@endsection
