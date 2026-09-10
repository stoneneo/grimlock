<?php

namespace GorillaSoft\Grimlock\Module\Notification\Firebase;

use Exception;
use Google\Auth\Credentials\ServiceAccountCredentials;
use GorillaSoft\Grimlock\Core\Collection\StringMap;
use GorillaSoft\Grimlock\Core\Exception\CoreException;
use GorillaSoft\Grimlock\Core\Helper\FileHelper;
use GorillaSoft\Grimlock\Core\Helper\TemplateHelper;
use GorillaSoft\Grimlock\Core\Helper\PropertyHelper;
use GorillaSoft\Grimlock\Module\Notification\Firebase\Dto\Notification;
use GorillaSoft\Grimlock\Module\Notification\Firebase\Dto\Person;
use GorillaSoft\Grimlock\Module\RestClient\RestClient;

/**
 * @author Ruben Dario Huamani Ucharima
 */
class Firebase implements FirebaseInterface
{
    private const string FIREBASE_SCOPE_MESSAGING = 'https://www.googleapis.com/auth/firebase.messaging';
    private const string FIREBASE_URL  = 'https://fcm.googleapis.com';

    private string $firebaseProject;


    private RestClient $restClient;

    /**
     * @param string $firebaseProject
     * @param string $firebaseConfig
     * @throws CoreException
     */
    public function __construct(string $firebaseProject, string $firebaseConfig)
    {
        if (empty($firebaseProject)) {
            throw new CoreException(self::class, 'Firebase Project is empty.');
        }
        if (empty($firebaseConfig)) {
            throw new CoreException(self::class, 'Firebase Config is empty.');
        }

        $this->firebaseProject = $firebaseProject;

        //Get Google Firebase Access Token
        $path = FileHelper::getCallerPath();
        $serviceAccountFile = FileHelper::resolvePath($path, $firebaseConfig);
        $scopes = [self::FIREBASE_SCOPE_MESSAGING];
        $googleCredentials = new ServiceAccountCredentials($scopes, $serviceAccountFile);
        $token = $googleCredentials->fetchAuthToken();
        $accessToken = $token['access_token'];

        //Create Rest Client
        $this->restClient = new RestClient(self::FIREBASE_URL, 2);
        $this->restClient->addHeader('Content-Length', '0');
        $this->restClient->addHeader('Authorization', 'Bearer ' . $accessToken);
    }

    /**
     * @param Notification $notification
     * @param StringMap<string>|null $params
     * @return bool
     * @throws CoreException
     */
    public function sendNotification(Notification $notification, ?StringMap $params = null): bool
    {
        try {
            if (!PropertyHelper::isNotEmpty($notification, 'title')) {
                throw new CoreException(self::class, 'Notification Title Not Null or Empty');
            }
            if (!PropertyHelper::isNotEmpty($notification, 'body')) {
                throw new CoreException(self::class, 'Notification Body Not Null or Empty');
            }

            $params ??= new StringMap();
            $title = TemplateHelper::replaceParams($notification->title, $params);
            $body = TemplateHelper::replaceParams($notification->body, $params);

            $body = [
                'message' => [
                    'topic' => $notification->topic,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                        'image' => $notification->image
                    ]
                ]
            ];

            $responseClient = $this->restClient->post('/v1/projects/'.$this->firebaseProject.'/messages:send', $body);

            return $responseClient->code === 200;
        } catch (Exception $e) {
            throw new CoreException(self::class, $e->getMessage());
        }
    }

    /**
     * @param Notification $notification
     * @param Person $person
     * @param StringMap<string>|null $params
     * @return bool
     * @throws CoreException
     */
    public function sendNotificationPerson(Notification $notification, Person $person, ?StringMap $params = null): bool
    {
        try {
            if (!PropertyHelper::isNotEmpty($notification, 'title')) {
                throw new CoreException(self::class, 'Notification Title Not Null or Empty');
            }
            if (!PropertyHelper::isNotEmpty($notification, 'body')) {
                throw new CoreException(self::class, 'Notification Body Not Null or Empty');
            }

            $params ??= new StringMap();

            $title = TemplateHelper::replaceParams($notification->title, $params);
            $body = TemplateHelper::replaceParams($notification->body, $params);
            $body = [
                'token' => $person->idRegistration,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                    'image' => $notification->image
                ]
            ];

            $responseClient = $this->restClient->post('/v1/projects/'.$this->firebaseProject.'/messages:send', $body);
            return $responseClient->code === 200;
        } catch (Exception $e) {
            throw new CoreException(self::class, $e->getMessage());
        }
    }

}
