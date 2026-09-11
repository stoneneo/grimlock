<?php

namespace GorillaSoft\Grimlock\Module\Mailer;

use GorillaSoft\Grimlock\Core\Collection\Collection;
use GorillaSoft\Grimlock\Core\Collection\StringMap;
use GorillaSoft\Grimlock\Core\Exception\CoreException;
use GorillaSoft\Grimlock\Core\Helper\TemplateHelper;
use GorillaSoft\Grimlock\Core\Helper\PropertyHelper;
use GorillaSoft\Grimlock\Module\Mailer\Settings\Settings;
use GorillaSoft\Grimlock\Module\Mailer\Dto\Attachment;
use GorillaSoft\Grimlock\Module\Mailer\Dto\Person;
use GorillaSoft\Grimlock\Module\Mailer\Dto\Sender;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use ReflectionException;

/**
 * Class Mailer SMTP
 * @package Grimlock
 */
class Mailer implements MailerInterface
{
    private PHPMailer $phpMailer;


    /**
     * @param Settings $mailSettings
     * @param bool $debug
     * @throws CoreException
     * @throws ReflectionException
     */
    public function __construct(Settings $mailSettings, bool $debug = false)
    {
        if (!PropertyHelper::isNotEmpty($mailSettings, 'host')) {
            throw new CoreException(self::class, 'Mail Host not found or empty');
        }
        if (!PropertyHelper::isNotEmpty($mailSettings, 'port')) {
            throw new CoreException(self::class, 'Mail Port not found or empty');
        }
        if (!PropertyHelper::isNotEmpty($mailSettings, 'username')) {
            throw new CoreException(self::class, 'Mail User not found or empty');
        }
        if (!PropertyHelper::isNotEmpty($mailSettings, 'password')) {
            throw new CoreException(self::class, 'Mail Pass not found or empty');
        }

        $this->phpMailer = new PHPMailer();
        $this->phpMailer->Host = $mailSettings->host;
        $this->phpMailer->Port = $mailSettings->port;
        $this->phpMailer->Username = $mailSettings->username;
        $this->phpMailer->Password = $mailSettings->password;
        $this->phpMailer->SMTPAuth = $mailSettings->mailAuth;
        $this->phpMailer->SMTPAutoTLS = $mailSettings->autoTls;
        $this->phpMailer->CharSet = "utf-8";
        $this->phpMailer->IsSMTP();
        if ($debug) {
            $this->phpMailer->SMTPDebug = 2;
        }
        $this->phpMailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->phpMailer->IsHTML();
    }

    /**
     *
     * @param Sender $mailSender
     * @param Person $mailPerson
     * @param Collection<Person>|null $lAddressCc
     * @param Collection<Person>|null $lAddressBcc
     * @param Collection<Attachment>|null $lAttachments
     * @throws CoreException
     */
    public function addRecipients(Sender $mailSender, Person $mailPerson, ?Collection $lAddressCc = null, ?Collection $lAddressBcc = null, ?Collection $lAttachments = null): void
    {
        try {
            $this->phpMailer->From = $mailSender->email;
            $this->phpMailer->FromName = $mailSender->name;
            $this->phpMailer->AddAddress($mailPerson->email, $mailPerson->name);

            if ($lAddressCc != null) {
                for ($i = 0; $i < $lAddressCc->size(); $i++) {
                    $ccAddress = $lAddressCc->get($i);
                    $this->phpMailer->addCC($ccAddress->email, $ccAddress->name);
                }
            }
            if ($lAddressBcc != null) {
                for ($i = 0; $i < $lAddressBcc->size(); $i++) {
                    $bccAddress = $lAddressBcc->get($i);
                    $this->phpMailer->addBCC($bccAddress->email, $bccAddress->name);
                }
            }

            if ($lAttachments != null) {
                for ($i = 0; $i < $lAttachments->size(); $i++) {
                    $bAttachment = $lAttachments->get($i);
                    $attachment = base64_decode($bAttachment->base64);
                    $this->phpMailer->addStringAttachment($attachment, $bAttachment->name, "base64", $bAttachment->type);
                }
            }
        } catch (Exception|Exception $e) {
            throw new CoreException(self::class, $e->getMessage());
        }
    }

    /**
     * Generate Text
     * @param string $subject
     * @param string $text
     * @param StringMap<string>|null $params
     * @return void
     */
    public function addText(string $subject, string $text, ?StringMap $params = null): void
    {
        $this->phpMailer->Subject = $subject;
        $this->phpMailer->Body = TemplateHelper::replaceParams($text, $params);
    }

    /**
     * Generate HTML
     * @param string $subject
     * @param string $html
     * @param StringMap<string>|null $params
     */
    public function addHtml(string $subject, string $html, ?StringMap $params = null): void
    {
        $this->phpMailer->Subject = $subject;
        $this->phpMailer->Body = TemplateHelper::replaceParams($html, $params);
    }

    /**
     * @throws Exception
     * @throws CoreException
     * @throws ReflectionException
     */
    public function sendMail(): bool
    {
        try {
            if (!PropertyHelper::isNotEmpty($this->phpMailer, 'From')) {
                throw new CoreException(self::class, "From is not set");
            }
            if (!PropertyHelper::isNotEmpty($this->phpMailer, 'FromName')) {
                throw new CoreException(self::class, "FromName is not set");
            }
            if ($this->phpMailer->getToAddresses() == null) {
                throw new CoreException(self::class, "To Addresses are not set");
            }
            if (!PropertyHelper::isNotEmpty($this->phpMailer, 'Subject')) {
                throw new CoreException(self::class, "Subject is not set");
            }
            if (!PropertyHelper::isNotEmpty($this->phpMailer, 'Body')) {
                throw new CoreException(self::class, "Body is not set");
            }

            $isSend = $this->phpMailer->send();

            $this->phpMailer->FromName = '';
            $this->phpMailer->From = '';
            $this->phpMailer->Sender = '';
            $this->phpMailer->Subject = '';
            $this->phpMailer->Body = '';
            $this->phpMailer->clearAddresses();
            $this->phpMailer->clearAttachments();
            $this->phpMailer->clearBCCs();
            $this->phpMailer->clearCCs();
            $this->phpMailer->clearCustomHeaders();
            $this->phpMailer->clearReplyTos();
            $this->phpMailer->clearAllRecipients();

            return $isSend;
        } catch (Exception $e) {
            throw new CoreException(self::class, $e->getMessage());
        }
    }

}
