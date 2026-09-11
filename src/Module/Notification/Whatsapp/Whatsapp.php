<?php

namespace GorillaSoft\Grimlock\Module\Notification\Whatsapp;

use GorillaSoft\Grimlock\Core\Collection\StringMap;
use GorillaSoft\Grimlock\Core\Exception\CoreException;
use GorillaSoft\Grimlock\Core\Helper\TemplateHelper;
use GorillaSoft\Grimlock\Module\Notification\Whatsapp\Dto\Person;
use GorillaSoft\Grimlock\Module\Notification\Whatsapp\Enum\Language;
use GorillaSoft\Grimlock\Module\RestClient\RestClient;

class Whatsapp implements WhatsappInterface
{
    private const string WHATSAPP_URL = 'https://graph.facebook.com/v25.0/';

    private RestClient $restClient;

    /**
     * @param string $accessToken
     * @param string $phoneNumberId
     * @throws CoreException
     */
    public function __construct(string $accessToken, string $phoneNumberId)
    {
        if (empty($accessToken)) {
            throw new CoreException(self::class, 'Access Token empty');
        }
        if (empty($phoneNumberId)) {
            throw new CoreException(self::class, 'Phone Number ID empty');
        }
        $this->restClient = new RestClient(self::WHATSAPP_URL.$phoneNumberId);
        $this->restClient->addHeader('Authorization', 'Bearer : '.$accessToken);
    }

    /**
     * @param Person $person
     * @param string $message
     * @param StringMap<string>|null $params
     * @return bool
     * @throws CoreException
     */
    public function sendMessage(Person $person, string $message, ?StringMap $params = null): bool
    {
        $params ??= new StringMap();

        $responseClient = $this->restClient->post('messages', $this->getBodyMessage($message, $person, $params));
        $httpCode = $responseClient->code;
        if ($httpCode == 200) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param Person $person
     * @param string $template
     * @param Language $language
     * @return bool
     * @throws CoreException
     */
    public function sendTemplate(Person $person, string $template, Language $language = Language::EN): bool
    {
        $responseClient = $this->restClient->post('messages', $this->getBodyTemplate($template, $person, $language));
        $httpCode = $responseClient->code;
        if ($httpCode == 200) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $message
     * @param Person $person
     * @param StringMap<string> $params
     * @return array<string, mixed>
     */
    private function getBodyMessage(string $message, Person $person, StringMap $params): array
    {
        $body = TemplateHelper::replaceParams($message, $params);
        return [
            'type' => 'text',
            'to' => $person->number,
            'recipient_type' => 'individual',
            'messaging_product' => 'whatsapp',
            'text' => [
                'body' => $body
            ]
        ];
    }

    /**
     * @param string $template
     * @param Person $person
     * @param Language $language
     * @return array<string,mixed>
     */
    private function getBodyTemplate(string $template, Person $person, Language $language): array
    {
        return [
            'type' => 'template',
            'to' => $person->number,
            'recipient_type' => 'individual',
            'messaging_product' => 'whatsapp',
            'template' => [
                'name' => $template,
                'language' => $language->value,
            ]
        ];
    }

}
