<?php

use App\Models\Setting;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Mail;

if (!function_exists('get_setting')) {
    function get_setting(string $key, $default = null)
    {
        static $settings = null;

        if ($settings === null) {
            $settings = Setting::pluck('value', 'key')->toArray();
        }

        return $settings[$key] ?? $default;
    }
}

if (!function_exists('replaceMacros')) {
    /**
     * Replace all macros in the format {MACRO_NAME} with actual values
     */
    function replaceMacros(string $content, array $macros = []): string
    {
        foreach ($macros as $macro => $value) {
            // Ensure {MACRO} format
            $placeholder = '{' . strtoupper(trim($macro, '{} ')) . '}';
            $content = str_replace($placeholder, $value, $content);
        }
        return $content;
    }
}

if (!function_exists('sendTemplateEmail')) {
    function sendTemplateEmail($slug, $toEmail, $macros = [])
    {
        try {
            $template = EmailTemplate::where('slug', $slug)->where('status', '1')->first();

            if (!$template) {
                \Log::error("Email template not found for slug: {$slug}");
                return false;
            }

            // Replace macros
            $subject = replaceMacros($template->subject, $macros);
            $body    = replaceMacros($template->email_content, $macros);

            Mail::send([], [], function ($message) use ($toEmail, $subject, $body) {
                $message->to($toEmail)
                    ->subject($subject)
                    ->from(
                        get_setting('from_address', config('mail.from.address')),
                        get_setting('from_name', config('mail.from.name'))
                    )
                    ->html($body);
            });

            \Log::info("✅ Email sent successfully to {$toEmail} using template: {$slug}");
            return true;
        } catch (\Exception $e) {
            \Log::error("❌ Failed to send email to {$toEmail}: " . $e->getMessage());
            return false;
        }
    }
}

// Alias functions for backward compatibility
if (!function_exists('sendVendorTemplateEmail')) {
    function sendVendorTemplateEmail($slug, $toEmail, $macros = [])
    {
        return sendTemplateEmail($slug, $toEmail, $macros);
    }
}

if (!function_exists('sendAdminTemplateEmail')) {
    function sendAdminTemplateEmail($slug, $toEmail, $macros = [])
    {
        return sendTemplateEmail($slug, $toEmail, $macros);
    }
}

if (!function_exists('newCustomerRegister')) {
    function newCustomerRegister($slug, $toEmail, $macros = [])
    {
        return sendTemplateEmail($slug, $toEmail, $macros);
    }
}

if (!function_exists('newBooking')) {
    function newBooking($slug, $toEmail, $macros = [])
    {
        return sendTemplateEmail($slug, $toEmail, $macros);
    }
}

if (!function_exists('SendPasswordResetEmail')) {
    function SendPasswordResetEmail($slug, $toEmail, $macros = [])
    {
        return sendTemplateEmail($slug, $toEmail, $macros);
    }
}

/*========================== Generate HTML Table for Booking ==========================*/
if (!function_exists('generateBookingDataTable')) {
    function generateBookingDataTable(array $data): string
    {
        if (empty($data)) {
            return '<p>No booking data available.</p>';
        }

        $html = '<table border="1" cellpadding="6" cellspacing="0" style="border-collapse: collapse; width: 100%;">';
        $html .= '<thead></thead><tbody>';

        foreach ($data as $key => $value) {
            $formattedKey = ucwords(str_replace('_', ' ', $key));

            if (is_array($value) || is_object($value)) {
                $value = implode(', ', (array)$value);
            } else {
                $value = (string)$value;
            }

            $html .= '<tr>';
            $html .= '<td><strong>' . htmlspecialchars($formattedKey, ENT_QUOTES, 'UTF-8') . '</strong></td>';
            $html .= '<td>' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';
        return $html;
    }
}
