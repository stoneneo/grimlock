<?php

namespace GorillaSoft\Grimlock\Tests\Module\Mailer;

use GorillaSoft\Grimlock\Core\Exception\CoreException;
use GorillaSoft\Grimlock\Module\Mailer\Core\MailSettings;
use GorillaSoft\Grimlock\Module\Mailer\Mailer;
use PHPUnit\Framework\TestCase;

class MailerTest extends TestCase
{

    /**
     * @throws CoreException
     */
    public function testGrimlockMailerException(): void
    {
        $mailSettings = new MailSettings();
        $mailSettings->host = "smtp.demo.com";
        $mailSettings->port = 0;
        $mailSettings->username = "demo";
        $mailSettings->password = "demo";
        $mailSettings->mailAuth = true;
        $mailSettings->autoTls = false;
        $this->expectException(CoreException::class);
        $grimlockMailer = new Mailer($mailSettings);

        $this->assertTrue(true);
    }

}
