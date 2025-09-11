<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        EmailTemplate::updateOrCreate(
            ['slug' => 'new_account_email'],
            [
                'title'   => 'New Account Email',
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
            ]
        );
    }
}
