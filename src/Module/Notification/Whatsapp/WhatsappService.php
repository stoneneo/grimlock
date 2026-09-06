<?php

namespace GorillaSoft\Grimlock\Module\Notification\Whatsapp;

use GorillaSoft\Grimlock\Core\Exception\CoreException;
use GorillaSoft\Grimlock\Module\Notification\Whatsapp\Dto\Person;
use GorillaSoft\Grimlock\Module\RestClient\RestClient;

class WhatsappService
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
        if ($accessToken === '')
        {
            throw new CoreException(self::class,  'Access Token empty');
        }
        if ($phoneNumberId === '')
        {
            throw new CoreException(self::class,  'Phone Number ID empty');
        }
        $this->restClient = new RestClient(self::WHATSAPP_URL.$phoneNumberId);
        $this->restClient->addHeader('Authorization', 'Bearer : '.$accessToken);
    }

    /**
     * @param Person $person
     * @param string $message
     * @return bool
     * @throws CoreException
     */
    public function sendMessage(Person $person, string $message): bool
    {
        $responseClient = $this->restClient->post('/messages', $this->getBodyMessage($message, $person));
        $httpCode = $responseClient->code;
        if ($httpCode == 200)
            return true;
        else
            return false;
    }

    /**
     * @param Person $person
     * @param string $template
     * @return bool
     * @throws CoreException
     */
    public function sendTemplate(Person $person, string $template): bool
    {
        $responseClient = $this->restClient->post('/messages', $this->getBodyTemplate($template, $person));
        $httpCode = $responseClient->code;
        if ($httpCode == 200)
            return true;
        else
            return false;
    }

    /**
     * @param string $message
     * @param Person $person
     * @return string
     */
    private function formatMessage(string $message, Person $person): string
    {
        $keys = array('{NAME}');
        $values = array($person->name);

        return str_replace($keys, $values, $message);
    }

    /**
     * @param string $message
     * @param Person $person
     * @return array
     */
    private function getBodyMessage(string $message, Person $person): array
    {
        return array(
            'type' => 'text',
            'to' => $person->number,
            'recipient_type' => 'individual',
            'messaging_product' => 'whatsapp',
            'text' => array(
                'body' => $this->formatMessage($message, $person)
            )
        );
    }

    /**
     * @param string $template
     * @param Person $person
     * @return array
     */
    private function getBodyTemplate(string $template, Person $person): array
    {
        return array(
            'type' => 'template',
            'to' => $person->number,
            'recipient_type' => 'individual',
            'messaging_product' => 'whatsapp',
            'template' => array(
                'name' => $template,
                'language' => 'es_ES'
            )
        );
    }

}