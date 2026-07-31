<?php

namespace GorillaSoft\Grimlock\Tests\Module\Mailer;

use GorillaSoft\Grimlock\Core\Exception\GrimlockException;
use GorillaSoft\Grimlock\Module\Mailer\Core\MailSettings;
use GorillaSoft\Grimlock\Module\Mailer\GrimlockMailer;
use PHPUnit\Framework\TestCase;

class GrimlockMailerTest extends TestCase
{

    /**
     * @throws GrimlockException
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
        $this->expectException(GrimlockException::class);
        $grimlockMailer = new GrimlockMailer($mailSettings);

        $this->assertTrue(true);
    }

}
