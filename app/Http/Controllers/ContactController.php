<?php

namespace App\Http\Controllers;

use App\Enums\LeadStage;
use App\Enums\SeoPageType;
use App\Events\LeadInquirySubmitted;
use App\Http\Requests\ContactSubmissionRequest;
use App\Mail\LeadAutoReplyMail;
use App\Mail\NewLeadInquiryMail;
use App\Models\LeadInquiry;
use App\Support\CaptchaVerifier;
use App\Support\SeoManager;
use App\Support\SiteSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class ContactController extends Controller
{
    public function show(SeoManager $seoManager): View
    {
        $seo = $seoManager->resolve(SeoPageType::Static->value, 'contact', [
            'title' => 'Contact',
            'description' => 'Reach out for project collaboration, freelance work, or product consulting.',
        ]);

        return view('pages.contact', compact('seo'));
    }

    public function submit(ContactSubmissionRequest $request, CaptchaVerifier $captchaVerifier): RedirectResponse
    {
        if (filled($request->input('website'))) {
            return back()->with('status', 'Thanks. Your request has been received.');
        }

        $captchaToken = $request->string('captcha_token')->toString();
        if (! $captchaVerifier->verify($captchaToken, $request->ip())) {
            return back()
                ->withErrors(['captcha_token' => 'Captcha verification failed. Please try again.'])
                ->withInput();
        }

        $lead = LeadInquiry::query()->create([
            'full_name' => $request->string('full_name')->toString(),
            'email' => $request->string('email')->toString(),
            'phone' => $request->string('phone')->toString() ?: null,
            'company' => $request->string('company')->toString() ?: null,
            'budget' => $request->string('budget')->toString() ?: null,
            'service_interest' => $request->string('service_interest')->toString() ?: null,
            'project_timeline' => $request->string('project_timeline')->toString() ?: null,
            'message' => $request->string('message')->toString(),
            'stage' => LeadStage::New->value,
            'source' => 'website',
            'metadata' => [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referer' => $request->headers->get('referer'),
            ],
        ]);

        $lead->activities()->create([
            'actor_id' => null,
            'activity_type' => 'created',
            'note' => 'Lead created from public contact form.',
            'payload' => ['source' => 'website'],
        ]);

        event(new LeadInquirySubmitted($lead));

        $adminEmail = SiteSettings::get('lead_notification_email', env('ADMIN_EMAIL', config('mail.from.address')));

        if (filled($adminEmail)) {
            try {
                Mail::to($adminEmail)->send(new NewLeadInquiryMail($lead));
            } catch (Throwable $exception) {
                Log::warning('Lead inquiry email notification failed.', [
                    'lead_id' => $lead->id,
                    'message' => $exception->getMessage(),
                ]);
            }
        }

        if (filled($lead->email)) {
            $siteName = (string) SiteSettings::get('site_name', config('app.name', 'Our Team'));
            $contactEmail = SiteSettings::get('contact_email', config('mail.from.address'));

            try {
                Mail::to($lead->email)->send(new LeadAutoReplyMail(
                    $lead,
                    $siteName,
                    filled($contactEmail) ? (string) $contactEmail : null
                ));
            } catch (Throwable $exception) {
                Log::warning('Lead auto-reply email failed.', [
                    'lead_id' => $lead->id,
                    'message' => $exception->getMessage(),
                ]);
            }
        }

        return back()->with('status', 'Thanks for reaching out. We will get back to you shortly.');
    }
}
