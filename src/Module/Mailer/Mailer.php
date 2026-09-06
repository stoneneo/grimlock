<?php

namespace GorillaSoft\Grimlock\Module\Mailer;

use GorillaSoft\Grimlock\Core\Collection\CollectionList;
use GorillaSoft\Grimlock\Core\Exception\CoreException;
use GorillaSoft\Grimlock\Module\Mailer\Core\MailSettings;
use GorillaSoft\Grimlock\Module\Mailer\Dto\MailPerson;
use GorillaSoft\Grimlock\Module\Mailer\Dto\MailSender;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Class Mailer SMTP
 * @package Grimlock
 */
class Mailer
{

    private PHPMailer $phpMailer;


    /**
     * @param MailSettings $mailSettings
     * @param bool $debug
     * @throws CoreException
     */
    public function __construct(MailSettings $mailSettings, bool $debug = false)
    {
        if ($mailSettings->host === '')
        {
            throw new CoreException(self::class,  'Mail Host not found or empty');
        }
        if ($mailSettings->port === 0)
        {
            throw new CoreException(self::class,  'Mail Port not found or empty');
        }
        if ($mailSettings->username === '')
        {
            throw new CoreException(self::class,  'Mail User not found or empty');
        }
        if ($mailSettings->password === '')
        {
            throw new CoreException(self::class,  'Mail Pass not found or empty');
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
        $this->phpMailer->SMTPSecure = 'tls';
        $this->phpMailer->IsHTML(true);
    }


    /**
     * Generate Mail
     * @throws CoreException
     * @throws Exception
     */
    public function generateMail(MailSender $sender, MailPerson $address, $subject, $body, CollectionList $lAddressCc = null, CollectionList $lAddressBcc = null, CollectionList $lAttachments = null): void
    {
        $this->phpMailer->From = $sender->email;
        $this->phpMailer->FromName = $sender->name;
        $this->phpMailer->AddAddress($address->email, $address->name);

        if($lAddressCc != null){
            for ($i = 0; $i < $lAddressCc->getSize(); $i++){
                $ccAddress = $lAddressCc->getItem($i);
                $this->phpMailer->addCC($ccAddress->getEMail(), $ccAddress->getName());
            }
        }
        if($lAddressBcc != null){
            for ($i = 0; $i < $lAddressBcc->getSize(); $i++){
                $bccAddress = $lAddressBcc->getItem($i);
                $this->phpMailer->addBCC($bccAddress->getEMail(), $bccAddress->getName());
            }
        }

        if($lAttachments != null){
            for($i = 0; $i < $lAttachments->getSize(); $i++){
                $bAttachment = $lAttachments->getItem($i);
                $attachment = base64_decode($bAttachment->getBase64());
                $this->phpMailer->addStringAttachment($attachment, $bAttachment->getName(), "base64", $bAttachment->getType());
            }
        }

        $this->phpMailer->Subject = $subject;
        $this->phpMailer->Body = $body;
    }

    /**
     * Generate HTML
     * @throws CoreException
     */
    public function generateHtml($html, CollectionList $lParameters = null): void
    {
        if($lParameters != null){
            for($i = 0; $i < $lParameters->getSize(); $i++){
                $parameter = $lParameters->getItem($i);
                $html = str_replace($parameter->getName(), $parameter->getValue(), $html);
            }
        }

        $this->phpMailer->Body = $html;
    }

    /**
     * @throws Exception
     */
    public function sendMail(): bool
    {
        return $this->phpMailer->send();
    }

}
