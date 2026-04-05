<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $siteName ?? config('app.name') }}</title>
</head>
@php
    $brandName = trim((string) ($siteName ?? config('app.name', 'Portfolio Core')));
    $initials = collect(explode(' ', $brandName))
        ->filter()
        ->map(fn (string $part) => strtoupper(substr($part, 0, 1)))
        ->take(2)
        ->implode('');
@endphp
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: Arial, sans-serif; color: #111827;">
    @if(! empty($preheader))
        <span style="display:none;max-height:0;max-width:0;opacity:0;overflow:hidden;line-height:1;font-size:1px;">
            {{ $preheader }}
        </span>
    @endif

    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #f3f4f6; margin: 0; padding: 24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width: 680px; background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 14px; overflow: hidden;">
                    <tr>
                        <td style="padding: 26px 28px; background: linear-gradient(120deg, #111827, #1f2937); color: #ffffff;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td valign="middle" style="width: 56px;">
                                        @if(! empty($logoUrl))
                                            <img src="{{ $logoUrl }}" alt="{{ $brandName }} logo" width="44" height="44" style="display:block;border-radius:10px;object-fit:cover;border:0;">
                                        @else
                                            <div style="width:44px;height:44px;border-radius:10px;background-color:#ffffff;color:#111827;text-align:center;line-height:44px;font-weight:700;font-size:14px;">
                                                {{ $initials !== '' ? $initials : 'PC' }}
                                            </div>
                                        @endif
                                    </td>
                                    <td valign="middle" style="padding-left: 10px;">
                                        <p style="margin:0;font-size:18px;font-weight:700;line-height:1.3;">{{ $brandName }}</p>
                                        @if(! empty($headerTitle))
                                            <p style="margin:4px 0 0;font-size:13px;line-height:1.4;color:#d1d5db;">{{ $headerTitle }}</p>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                            @if(! empty($headerSubtitle))
                                <p style="margin:14px 0 0;font-size:14px;line-height:1.6;color:#e5e7eb;">{{ $headerSubtitle }}</p>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 26px 28px;">
                            @yield('content')
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 14px 28px; background-color:#f9fafb; border-top:1px solid #e5e7eb; color:#6b7280; font-size:12px; line-height:1.5;">
                            <p style="margin:0;">This message was sent by {{ $brandName }}.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
