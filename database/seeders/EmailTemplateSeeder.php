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
                    <p>Dear {NAME},<br /><br />
                    Welcome to <strong>{SITE_TITLE}</strong>! Your account has been successfully created. Please find your login credentials below:<br /><br />
                    Name: <strong>{NAME}</strong><br />
                    Email: <strong>{EMAIL}</strong><br />
                    Password: <strong>{PASSWORD}</strong><br /><br />
                    For security reasons, we recommend that you change your password after your first login.<br /><br />
                    Regards,<br /> {SITE_TITLE}</p>
                ',
                'dummy_template' => '
                    <p>Dear {NAME},<br /><br />
                    Welcome to <strong>{SITE_TITLE}</strong>! Your account has been successfully created. Please find your login credentials below:<br /><br />
                    Name: <strong>{NAME}</strong><br />
                    Email: <strong>{EMAIL}</strong><br />
                    Password: <strong>{PASSWORD}</strong><br /><br />
                    For security reasons, we recommend that you change your password after your first login.<br /><br />
                    Regards,<br /> {SITE_TITLE}</p>
                ',
                'macro' => '{NAME},{SITE_TITLE},{EMAIL},{PASSWORD}',
            ],
            [
                'slug' => 'new_account_email_notification',
                'title' => 'New Account Email Notification',
                'subject' => 'Welcome to {SITE_TITLE}',
                'email_content' => '
                    <p>Dear {NAME},<br /><br />
                    Welcome to <strong>{SITE_TITLE}</strong>! Your account has been successfully created. Please find your login credentials below:<br /><br />
                    Name: <strong>{NAME}</strong><br />
                    Email: <strong>{EMAIL}</strong><br />
                    For security reasons, we recommend that you change your password after your first login.<br /><br />
                    Regards,<br /> {SITE_TITLE}</p>
                ',
                'dummy_template' => '
                    <p>Dear {NAME},<br /><br />
                    Welcome to <strong>{SITE_TITLE}</strong>! Your account has been successfully created. Please find your login credentials below:<br /><br />
                    Name: <strong>{NAME}</strong><br />
                    Email: <strong>{EMAIL}</strong><br />
                    Password: <strong>{PASSWORD}</strong><br /><br />
                    For security reasons, we recommend that you change your password after your first login.<br /><br />
                    Regards,<br /> {SITE_TITLE}</p>
                ',
                'macro' => '{NAME},{SITE_TITLE},{EMAIL}',
            ],
            [
                'slug' => 'admin_new_user_notification',
                'title' => 'Admin New User Notification',
                'subject' => 'A new user has registered on {SITE_TITLE}',
                'email_content' => '
                    <p>Dear Admin,<br /><br />
                    A new user has just been created on <strong>{SITE_TITLE}</strong>.<br /><br />
                    Name: <strong>{NAME}</strong><br />
                    Email: <strong>{EMAIL}</strong><br /><br />
                    You can view and manage this user from your admin panel.<br /><br />
                    Regards,<br /> {SITE_TITLE}</p>
                ',
                'dummy_template' => '
                    <p>Dear Admin,<br /><br />
                    A new user has just been created on <strong>{SITE_TITLE}</strong>.<br /><br />
                    Name: <strong>{NAME}</strong><br />
                    Email: <strong>{EMAIL}</strong><br /><br />
                    You can view and manage this user from your admin panel.<br /><br />
                    Regards,<br /> {SITE_TITLE}</p>
                ',
                'macro' => '{NAME},{SITE_TITLE},{EMAIL}',
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
