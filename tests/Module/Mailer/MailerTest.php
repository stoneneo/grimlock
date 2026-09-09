<?php

namespace GorillaSoft\Grimlock\Tests\Module\Mailer;

use GorillaSoft\Grimlock\Core\Collection\Collection;
use GorillaSoft\Grimlock\Core\Collection\StringMap;
use GorillaSoft\Grimlock\Core\Exception\CoreException;
use GorillaSoft\Grimlock\Module\Mailer\Core\MailSettings;
use GorillaSoft\Grimlock\Module\Mailer\Dto\MailAttachment;
use GorillaSoft\Grimlock\Module\Mailer\Dto\MailPerson;
use GorillaSoft\Grimlock\Module\Mailer\Dto\MailSender;
use GorillaSoft\Grimlock\Module\Mailer\Mailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

class MailerTest extends TestCase
{
    private MailSettings $validSettings;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validSettings = new MailSettings();
        $this->validSettings->host = 'smtp.gmail.com';
        $this->validSettings->port = 587;
        $this->validSettings->username = 'user@gmail.com';
        $this->validSettings->password = 'secret';
        $this->validSettings->mailAuth = true;
        $this->validSettings->autoTls = true;
    }

    private function getInternalPhpMailer(Mailer $mailer): PHPMailer
    {
        $reflection = new ReflectionClass(Mailer::class);
        $property = $reflection->getProperty('phpMailer');
        $property->setAccessible(true);

        return $property->getValue($mailer);
    }

    /**
     * @throws CoreException
     * @throws ReflectionException
     */
    public function testConstructorInitializesPhpMailerSuccessfully(): void
    {
        $mailer = new Mailer($this->validSettings, true);
        $phpMailer = $this->getInternalPhpMailer($mailer);

        $this->assertEquals('smtp.gmail.com', $phpMailer->Host);
        $this->assertEquals(587, $phpMailer->Port);
        $this->assertEquals('user@gmail.com', $phpMailer->Username);
        $this->assertEquals('secret', $phpMailer->Password);
        $this->assertTrue($phpMailer->SMTPAuth);
        $this->assertTrue($phpMailer->SMTPAutoTLS);
        $this->assertEquals('utf-8', $phpMailer->CharSet);
        $this->assertEquals(2, $phpMailer->SMTPDebug);
    }

    /**
     * @throws ReflectionException
     */
    public function testConstructorThrowsExceptionWhenHostIsEmpty(): void
    {
        $this->expectException(CoreException::class);
        $this->validSettings->host = '';

        new Mailer($this->validSettings);
    }

    /**
     * @throws ReflectionException
     */
    public function testConstructorThrowsExceptionWhenUsernameIsEmpty(): void
    {
        $this->expectException(CoreException::class);
        $this->validSettings->username = '';

        new Mailer($this->validSettings);
    }

    /**
     * @throws ReflectionException
     */
    public function testConstructorThrowsExceptionWhenPasswordIsEmpty(): void
    {
        $this->expectException(CoreException::class);
        $this->validSettings->password = '';

        new Mailer($this->validSettings);
    }

    /**
     * @throws Exception
     * @throws CoreException
     * @throws ReflectionException
     */
    public function testAddRecipientsAndAttachments(): void
    {
        $mailer = new Mailer($this->validSettings);

        $sender = new MailSender();
        $sender->name = 'System Sender';
        $sender->email = 'sender@test.com';
        $personTo = new MailPerson();
        $personTo->name = 'Main Recipient';
        $personTo->email = 'to@test.com';

        // CC Collection.php
        /** @var Collection<MailPerson> $ccList */
        $ccList = new Collection();
        $ccPerson = new MailPerson();
        $ccPerson->name = 'CC User';
        $ccPerson->email = 'cc@test.com';
        $ccList->append($ccPerson);

        // BCC Collection.php
        /** @var Collection<MailPerson> $bccList */
        $bccList = new Collection();
        $bccPerson = new MailPerson();
        $bccPerson->name = 'BCC User';
        $bccPerson->email = 'bcc@test.com';
        $bccList->append($bccPerson);

        // Attachment Collection.php
        /** @var Collection<MailAttachment> $attachmentList */
        $attachmentList = new Collection();
        $attachment = new MailAttachment();
        $attachment->name = 'document.pdf';
        $attachment->base64 = base64_encode('PDF Content Dummy');
        $attachment->type = 'application/pdf';
        $attachmentList->append($attachment);

        $mailer->addRecipients($sender, $personTo, $ccList, $bccList, $attachmentList);

        $phpMailer = $this->getInternalPhpMailer($mailer);

        $this->assertEquals('sender@test.com', $phpMailer->From);
        $this->assertEquals('System Sender', $phpMailer->FromName);
        $this->assertCount(1, $phpMailer->getToAddresses());
        $this->assertCount(1, $phpMailer->getCcAddresses());
        $this->assertCount(1, $phpMailer->getBccAddresses());
        $this->assertCount(1, $phpMailer->getAttachments());
    }

    /**
     * @throws CoreException
     * @throws ReflectionException
     */
    public function testAddTextReplacesParametersCorrectly(): void
    {
        $mailer = new Mailer($this->validSettings);

        $parameters = new StringMap();
        $parameters->put('name', 'Joe');
        $parameters->put('code', '998877');

        $mailer->addText('Confirmation', 'Hello :name, your code is :code', $parameters);

        $phpMailer = $this->getInternalPhpMailer($mailer);

        $this->assertEquals('Confirmation', $phpMailer->Subject);
        $this->assertEquals('Hello Joe, your code is 998877', $phpMailer->Body);
    }

    /**
     * @throws CoreException
     * @throws ReflectionException
     */
    public function testAddHtmlSetsBodyAndSubject(): void
    {
        $mailer = new Mailer($this->validSettings);

        $parameters = new StringMap();
        $parameters->put('title', 'Welcome');

        $mailer->addHtml('Email HTML', '<h1>:title</h1>', $parameters);

        $phpMailer = $this->getInternalPhpMailer($mailer);

        $this->assertEquals('Email HTML', $phpMailer->Subject);
        $this->assertEquals('<h1>Welcome</h1>', $phpMailer->Body);
    }

    /**
     * @throws Exception
     * @throws CoreException
     * @throws ReflectionException
     */
    public function testSendMailFailsWhenValidationNotMet(): void
    {
        $mailer = new Mailer($this->validSettings);

        $this->expectException(CoreException::class);
        $mailer->sendMail();
    }

    /**
     * @throws Exception
     * @throws CoreException
     * @throws ReflectionException
     */
    public function testSendMailReturnsTrueWithMockedPhpMailer(): void
    {
        $mailer = new Mailer($this->validSettings);

        $phpMailerMock = $this->createMock(PHPMailer::class);
        $phpMailerMock->expects($this->once())
            ->method('send')
            ->willReturn(true);

        $phpMailerMock->method('getToAddresses')
            ->willReturn([['to@test.com', 'Main Recipient']]);

        $reflection = new ReflectionClass(Mailer::class);
        $property = $reflection->getProperty('phpMailer');
        $property->setAccessible(true);
        $property->setValue($mailer, $phpMailerMock);

        $sender = new MailSender();
        $sender->name = 'System Sender';
        $sender->email = 'sender@test.com';
        $recipient = new MailPerson();
        $recipient->name = 'Main Recipient';
        $recipient->email = 'to@test.com';

        $mailer->addRecipients($sender, $recipient);
        $mailer->addText('Subject Test', 'Message Body');


        $result = $mailer->sendMail();
        $this->assertTrue($result);
    }

}
