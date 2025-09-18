<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'slug'   => 'vendor_login_email_notification',
                'title'   => 'Vendor Login Email Notification',
                'subject' => 'Welcome to {SITE_TITLE}',
                'email_content'    => '
                    <p>Dear {USER_NAME},<br /><br />
                    Welcome to <strong>{SITE_TITLE}</strong>! Your account has been successfully created. Please find your login credentials below:<br /><br />
                    Name: <strong>{USER_NAME}</strong><br />
                    Email: <strong>{USER_EMAIL}</strong><br />
                    Password: <strong>{PASSWORD}</strong><br /><br />
                    For security reasons, we recommend that you change your password after your first login.<br /><br />
                    Regards,<br /> {SITE_TITLE}</p>
                ',
            ],
            [
                'slug' => 'new_account_email_notification',
                'title' => 'New Account Email Notification',
                'subject' => 'Welcome to {SITE_TITLE}',
                'email_content' => '
                    <p>Dear {USER_NAME},<br /><br />
                    Welcome to <strong>{SITE_TITLE}</strong>! Your account has been successfully created. Please find your login credentials below:<br /><br />
                    Name: <strong>{USER_NAME}</strong><br />
                    Email: <strong>{USER_EMAIL}</strong><br />
                    For security reasons, we recommend that you change your password after your first login.<br /><br />
                    Regards,<br /> {SITE_TITLE}</p>
                ',
            ],
            [
                'slug' => 'admin_new_user_notification',
                'title' => 'Admin New User Notification',
                'subject' => 'A new user has registered on {SITE_TITLE}',
                'email_content' => '
                    <p>Dear Admin,<br /><br />
                    A new user has just been created on <strong>{SITE_TITLE}</strong>.<br /><br />
                    Name: <strong>{USER_NAME}</strong><br />
                    Email: <strong>{USER_EMAIL}</strong><br /><br />
                    You can view and manage this user from your admin panel.<br /><br />
                    Regards,<br /> {SITE_TITLE}</p>
                ',
            ],
            [
                'slug' => 'booking_confirmed_notification',
                'title' => 'Booking Confirmed Notification',
                'subject' => 'Booking Confirmed – {SITE_TITLE}',
                'email_content' => '
                    <p>Dear {USER_NAME},</p>
                    <p>Thank you for your booking at <strong>{SITE_TITLE}</strong>.</p>
                    <div>
                        We’re happy to confirm that your booking has been successfully received.<br /><br />
                        <strong>Booking Details:</strong><br />
                        {BOOKING_DATA}
                    </div>
                    <p>
                        If you have any questions or need to make changes, feel free to contact us or manage your booking through your account.
                    </p>
                    <p>
                        Best regards,<br />
                        The <strong>{SITE_TITLE}</strong> Team
                    </p>
                ',
            ],
            [
                'slug' => 'password_reset_email',
                'title' => 'Password Reset Email',
                'subject' => 'Password Reset Request – {SITE_TITLE}',
                'email_content' => '
                        <p>Dear {USER_NAME},</p>
                        <p>We received a request to reset your password for your account at <strong>{SITE_TITLE}</strong>.</p>
                        <p>You can reset your password by clicking the link below:</p>
                        <p><a href="{RESET_LINK}">Reset Your Password</a></p>
                        <p>If you did not request a password reset, please ignore this email or contact support.</p>
                        <p>Best regards,<br />The <strong>{SITE_TITLE}</strong> Team</p>
                    ',
            ],
            [
                'slug'   => 'subscription_email_notification',
                'title'   => 'Subscription Email Notification',
                'subject' => 'Thank you for subscribing to {SITE_TITLE}',
                'email_content'    => '
                    Thank you for subscribing to <strong>{SITE_TITLE}</strong>! 🎉<br /><br />
                    We’re excited to have you with us. You will now receive the latest news, updates, and exclusive offers directly in your inbox.<br /><br />
                    If you ever wish to unsubscribe, you can do so easily from any of our emails.<br /><br />
                    Best regards,<br />
                    <strong>{SITE_TITLE}</strong></p>
                ',
            ],
            [
                'slug'   => 'enquiry_email_notification',
                'title'   => 'Enquiry Email Notification',
                'subject' => 'Thank You for Contacting {SITE_TITLE}',
                'email_content'    => '
                    Hi {USER_NAME},Thank you for reaching out to {SITE_TITLE}! 🎉
                    We’ve received your message and our team will get back to you shortly.
                    Best regards,
                    {SITE_TITLE} Team
                ',
            ],
            [
                'slug'   => 'admin_enquiry_email_notification',
                'title'   => 'Admin Enquiry Email Notification',
                'subject' => 'New Contact Inquiry from {USER_NAME}',
                'email_content'    => '
                    Hi Admin,
                    You have received a new contact inquiry from your website:
                    Name: {USER_NAME}
                    Email: {USER_EMAIL}
                    Phone: {PHONE}
                    Message: {MESSAGE}  
                    Please review and follow up as needed.
                    Best regards,
                    {SITE_TITLE} Website
                ',
            ],

        ];

        foreach ($templates as $template) {
            EmailTemplate::updateOrCreate(
                ['slug' => $template['slug']],
                $template
            );
        }
    }
}
