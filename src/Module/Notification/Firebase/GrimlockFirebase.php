<?php

namespace GorillaSoft\Grimlock\Module\Notification\Firebase;

use Exception;
use Google\Auth\Credentials\ServiceAccountCredentials;
use GorillaSoft\Grimlock\Core\Exception\GrimlockException;
use GorillaSoft\Grimlock\Core\Util\GrimlockUtil;
use GorillaSoft\Grimlock\Module\Notification\Firebase\Bean\Notification;
use GorillaSoft\Grimlock\Module\Notification\Firebase\Bean\Person;
use GorillaSoft\Grimlock\Module\Notification\Util\Constant;
use GorillaSoft\Grimlock\Module\RestClient\GrimlockRestClient;

/**
 * @author Ruben Dario Huamani Ucharima
 */
class GrimlockFirebase
{

    private string $firebaseProject;


    private GrimlockRestClient $restClient;

    /**
     * @param string $firebaseProject
     * @param string $firebaseKey
     * @throws GrimlockException
     */
    public function __construct(string $firebaseProject, string $firebaseKey)
    {
        if ($firebaseProject === '')
        {
            throw new GrimlockException(self::class,  'Parameter Construct Firebase Cloud Messaging Key');
        }
        if ($firebaseKey === '')
        {
            throw new GrimlockException(self::class,  'Parameter Construct Firebase Key');
        }

        $this->firebaseProject = $firebaseProject;

        //Get Google Firebase Access Token
        $path = GrimlockUtil::getCallerPath();
        $serviceAccountFile = GrimlockUtil::resolvePath($path, $firebaseKey);
        $scopes = [Constant::FIREBASE_SCOPE_MESSAGING];
        $googleCredentials = new ServiceAccountCredentials($scopes, $serviceAccountFile);
        $token = $googleCredentials->fetchAuthToken();
        $accessToken = $token['access_token'];

        //Create Rest Client
        $this->restClient = new GrimlockRestClient(Constant::FIREBASE_URL,2);
        $this->restClient->addHeader('Content-Length', '0');
        $this->restClient->addHeader('Authorization', 'Bearer ' . $accessToken);
    }

    /**
     * @param Notification $notification
     * @return bool
     * @throws GrimlockException
     */
    public function sendNotification(Notification $notification): bool {
        try {
            if ($notification->title == null || empty($notification->title)) {
                throw new GrimlockException(self::class,  'Notification Title Not Null or Empty');
            }

            $body = array(
                'message' => array(
                    'topic' => $notification->topic,
                    'notification' => array(
                        'title' => $notification->title,
                        'body' => $notification->body,
                        'image' => $notification->image
                    )
                )
            );

            $responseClient = $this->restClient->post('/v1/projects/'.$this->firebaseProject.'/messages:send', $body);

            return $responseClient->getCode() === 200;
        } catch (Exception $e) {
            throw new GrimlockException(self::class, $e->getMessage());
        }
    }

    /**
     * @param Notification $notification
     * @param Person $person
     * @return bool
     * @throws GrimlockException
     */
    public function sendNotificationPerson(Notification $notification, Person $person): bool
    {
        try {
            if ($notification->title == null || empty($notification->title)) {
                throw new GrimlockException(self::class,  'Notification Title Not Null or Empty');
            }

            $body = array(
                'token' => $person->idRegistration,
                'notification' => array(
                    'title' => $notification->title,
                    'body' => $this->formatMessage($notification->body, $person),
                    'image' => $notification->image
                )
            );

            $responseClient = $this->restClient->post('/v1/projects/'.$this->firebaseProject.'/messages:send', $body);
            return $responseClient->getCode() === 200;
        } catch (Exception $e) {
            throw new GrimlockException(self::class, $e->getMessage());
        }
    }

    private function formatMessage(string $message, Person $person): string
    {
        $keys = array('{NAME}', '{LASTNAME}');
        $values = array($person->name, $person->lastname);

        return str_replace($keys, $values, $message);
    }

}
