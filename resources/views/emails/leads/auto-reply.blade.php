@extends('emails.layouts.brand-shell')

@section('content')
    <p style="margin: 0 0 16px; color: #374151; font-size: 14px; line-height: 1.6;">
        Thanks for reaching out, <strong>{{ $leadInquiry->full_name }}</strong>. Your inquiry has been received and our team will contact you with next steps soon.
    </p>

    <div style="border:1px solid #e5e7eb; border-radius:10px; padding:14px; background:#fafafa; margin-bottom:18px;">
        <p style="margin:0 0 8px; font-size:13px; color:#4b5563;"><strong>Inquiry Summary</strong></p>
        <p style="margin:0 0 6px; font-size:14px; color:#111827;"><strong>Service:</strong> {{ $leadInquiry->service_interest ?: 'Not specified' }}</p>
        <p style="margin:0 0 6px; font-size:14px; color:#111827;"><strong>Timeline:</strong> {{ $leadInquiry->project_timeline ?: 'Not specified' }}</p>
        <p style="margin:0; font-size:14px; color:#111827;"><strong>Budget:</strong> {{ $leadInquiry->budget ?: 'Not specified' }}</p>
    </div>

    <table cellpadding="0" cellspacing="0" border="0" role="presentation" style="margin-bottom: 14px;">
        <tr>
            @if(! empty($caseStudiesUrl))
                <td style="padding-right: 10px;">
                    <a href="{{ $caseStudiesUrl }}" style="display:inline-block; padding:10px 16px; border-radius:8px; background:#111827; color:#ffffff; text-decoration:none; font-size:13px; font-weight:600;">
                        View Case Studies
                    </a>
                </td>
            @endif

            @if(! empty($contactUrl))
                <td>
                    <a href="{{ $contactUrl }}" style="display:inline-block; padding:10px 16px; border-radius:8px; background:#ffffff; border:1px solid #d1d5db; color:#111827; text-decoration:none; font-size:13px; font-weight:600;">
                        Update Your Inquiry
                    </a>
                </td>
            @endif
        </tr>
    </table>

    <p style="margin: 0 0 10px; color: #374151; font-size: 14px; line-height: 1.6;">
        You can reply directly to this email if you want to add more details before our response.
    </p>

    @if(filled($contactEmail))
        <p style="margin:0 0 8px; color:#4b5563; font-size:13px;">
            Direct support: <a href="mailto:{{ $contactEmail }}" style="color:#111827;">{{ $contactEmail }}</a>
        </p>
    @endif

    @if(! empty($siteUrl))
        <p style="margin:0; color:#4b5563; font-size:13px;">
            Visit website:
            <a href="{{ $siteUrl }}" style="color:#111827;">{{ $siteUrl }}</a>
        </p>
    @endif
@endsection
