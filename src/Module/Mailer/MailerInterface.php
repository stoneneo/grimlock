<?php

namespace GorillaSoft\Grimlock\Module\Mailer;

use GorillaSoft\Grimlock\Core\Collection\Collection;
use GorillaSoft\Grimlock\Core\Collection\StringMap;
use GorillaSoft\Grimlock\Module\Mailer\Dto\Attachment;
use GorillaSoft\Grimlock\Module\Mailer\Dto\Person;
use GorillaSoft\Grimlock\Module\Mailer\Dto\Sender;

interface MailerInterface
{
    /**
     * @param Sender $mailSender
     * @param Person $mailPerson
     * @param Collection<Person>|null $lAddressCc
     * @param Collection<Person>|null $lAddressBcc
     * @param Collection<Attachment>|null $lAttachments
     * @return void
     */
    public function addRecipients(Sender $mailSender, Person $mailPerson, ?Collection $lAddressCc = null, ?Collection $lAddressBcc = null, ?Collection $lAttachments = null): void;

    /**
     * Generate Text
     * @param string $subject
     * @param string $text
     * @param StringMap<string>|null $params
     * @return void
     */
    public function addText(string $subject, string $text, ?StringMap $params = null): void;

    /**
     * @param string $subject
     * @param string $html
     * @param StringMap<string>|null $params
     * @return void
     */
    public function addHtml(string $subject, string $html, ?StringMap $params = null): void;

    /**
     * @return bool
     */
    public function sendMail(): bool;

}
