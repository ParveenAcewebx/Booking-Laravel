<?php

use App\Models\Setting;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Mail;

if (!function_exists('get_setting')) {
    function get_setting(string $key, $default = null)
    {
        static $settings = null;

        // Cache settings for single request
        if ($settings === null) {
            $settings = Setting::pluck('value', 'key')->toArray();
        }

        return $settings[$key] ?? $default;
    }
}

if (!function_exists('replaceMacros')) {
    function replaceMacros($content, $macros = [], $allowedMacros = [])
    {
        foreach ($macros as $macro => $value) {
            // check agar macro allowed list me hai aur content me exist karta hai tabhi replace karega
            if (in_array($macro, $allowedMacros) && strpos($content, $macro) !== false) {
                $content = str_replace($macro, $value, $content);
            }
        }
        return $content;
    }
}

if (!function_exists('sendVendorTemplateEmail')) {
    function sendVendorTemplateEmail($slug, $toEmail, $macros = [])
    {
        try {
            $template = EmailTemplate::where('slug', $slug)->first();

            if (!$template) {
                \Log::error("Email template not found for slug: {$slug}");
                return false;
            }

            // macros ko explode karke array bna lo
            $allowedMacros = array_map('trim', explode(',', $template->macro));

            $subject = replaceMacros($template->subject, $macros, $allowedMacros);
            $body    = replaceMacros($template->email_content, $macros, $allowedMacros);

            Mail::html($body, function ($message) use ($toEmail, $subject) {
                $message->to($toEmail)
                        ->subject($subject)
                        ->from(
                            get_setting('from_address', config('mail.from.address')),
                            get_setting('from_name', config('mail.from.name'))
                        );
            });

            \Log::info("✅ Email sent successfully to {$toEmail} using template: {$slug}");
            return true;

        } catch (\Exception $e) {
            \Log::error("❌ Failed to send email to {$toEmail}: " . $e->getMessage());
            return false;
        }
    }
}
