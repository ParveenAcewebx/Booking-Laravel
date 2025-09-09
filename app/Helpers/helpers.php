<?php

use App\Models\Setting;
use App\Models\EmailTemplate;

use Illuminate\Support\Facades\Mail;

if (!function_exists('get_setting')) {
    function get_setting(string $key, $default = null)
    {
        $setting = Setting::where('key', $key)->first();
        return $setting ? $setting->value : $default;
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

            $subject = replaceMacros($template->subject, $macros);
            $body = replaceMacros($template->email_content, $macros); 
            Mail::html($body, function ($message) use ($toEmail, $subject) {
                $message->to($toEmail)
                        ->subject($subject)
                        ->from(get_setting('from_address'),  get_setting('from_name'));
            });
            \Log::info("✅ Email sent successfully to {$toEmail} using template: {$slug}");
            return true;

        } catch (\Exception $e) {
            \Log::error("❌ Failed to send email to {$toEmail}: " . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('replaceMacros')) {
    function replaceMacros($content, $macros = [])
    {
        foreach ($macros as $macro => $value) {
            $content = str_replace($macro, $value, $content);
        }
        return $content;
    }
}
