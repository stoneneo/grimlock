<?php

namespace GorillaSoft\Grimlock\Tests\Module\Notification\Firebase;

use Exception;
use GorillaSoft\Grimlock\Core\Exception\CoreException;
use GorillaSoft\Grimlock\Module\Notification\Firebase\Dto\Notification;
use GorillaSoft\Grimlock\Module\Notification\Firebase\Dto\Person;
use GorillaSoft\Grimlock\Module\Notification\Firebase\FirebaseService;
use GorillaSoft\Grimlock\Module\RestClient\Dto\Response;
use GorillaSoft\Grimlock\Module\RestClient\RestClient;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

class FirebaseServiceTest extends TestCase
{
    private string $firebaseProject = 'test-firebase-project';
    private string $firebaseKey = 'credentials.json';


    private function injectMockRestClient(FirebaseService $service, RestClient $mockRestClient): void
    {
        $reflection = new ReflectionClass(FirebaseService::class);
        $property = $reflection->getProperty('restClient');
        $property->setAccessible(true);
        $property->setValue($service, $mockRestClient);
    }

    private function createServiceWithoutConstructor(): FirebaseService
    {
        $reflection = new ReflectionClass(FirebaseService::class);
        $service = $reflection->newInstanceWithoutConstructor();

        $projectProp = $reflection->getProperty('firebaseProject');
        $projectProp->setAccessible(true);
        $projectProp->setValue($service, $this->firebaseProject);

        return $service;
    }

    public function testConstructorThrowsExceptionWhenProjectIsEmpty(): void
    {
        $this->expectException(CoreException::class);
        new FirebaseService('', $this->firebaseKey);
    }

    public function testConstructorThrowsExceptionWhenKeyIsEmpty(): void
    {
        $this->expectException(CoreException::class);
        new FirebaseService($this->firebaseProject, '');
    }

    public function testSendNotificationSuccess(): void
    {
        $service = $this->createServiceWithoutConstructor();

        $mockResponse = $this->createMock(Response::class);
        $mockResponse->code = 200;

        $mockRestClient = $this->createMock(RestClient::class);
        $mockRestClient->expects($this->once())
            ->method('post')
            ->with(
                '/v1/projects/' . $this->firebaseProject . '/messages:send',
                [
                    'message' => [
                        'topic' => 'news',
                        'notification' => [
                            'title' => 'Title',
                            'body' => 'News',
                            'image' => 'https://image.com/news.jpg'
                        ]
                    ]
                ]
            )
            ->willReturn($mockResponse);

        $this->injectMockRestClient($service, $mockRestClient);

        $notification = new Notification();
        $notification->topic = 'news';
        $notification->title = 'Title';
        $notification->body = 'News';
        $notification->image = 'https://image.com/news.jpg';

        $result = $service->sendNotification($notification);
        $this->assertTrue($result);
    }

    public function testSendNotificationThrowsExceptionWhenTitleIsEmpty(): void
    {
        $service = $this->createServiceWithoutConstructor();

        $notification = new Notification();
        $notification->title = '';

        $this->expectException(CoreException::class);
        $service->sendNotification($notification);
    }

    public function testSendNotificationThrowsCoreExceptionOnRestClientError(): void
    {
        $service = $this->createServiceWithoutConstructor();

        $mockRestClient = $this->createMock(RestClient::class);
        $mockRestClient->method('post')
            ->willThrowException(new Exception("Error Conection HTTP"));

        $this->injectMockRestClient($service, $mockRestClient);

        $notification = new Notification();
        $notification->title = 'Title';
        $notification->topic = '';

        $this->expectException(CoreException::class);
        $service->sendNotification($notification);
    }

    public function testSendNotificationPersonSuccessAndFormatsMessage(): void
    {
        $service = $this->createServiceWithoutConstructor();

        $mockResponse = $this->createMock(Response::class);
        $mockResponse->code = 200;

        $mockRestClient = $this->createMock(RestClient::class);
        $mockRestClient->expects($this->once())
            ->method('post')
            ->with(
                '/v1/projects/' . $this->firebaseProject . '/messages:send',
                [
                    'token' => 'fcm_registration_token_123',
                    'notification' => [
                        'title' => 'Welcome',
                        'body' => 'Hello Joe Doe',
                        'image' => 'https://image.com/avatar.png'
                    ]
                ]
            )
            ->willReturn($mockResponse);

        $this->injectMockRestClient($service, $mockRestClient);

        $notification = new Notification();
        $notification->title = 'Welcome';
        $notification->body = 'Hello Joe Doe';
        $notification->image = 'https://image.com/avatar.png';

        $person = new Person();
        $person->idRegistration = 'fcm_registration_token_123';
        $person->name = 'Joe';
        $person->lastname = 'Doe';

        $result = $service->sendNotificationPerson($notification, $person);
        $this->assertTrue($result);
    }

    public function testSendNotificationPersonThrowsExceptionWhenTitleIsNull(): void
    {
        $service = $this->createServiceWithoutConstructor();

        $notification = new Notification();
        $notification->title = '';

        $person = new Person();

        $this->expectException(CoreException::class);
        $service->sendNotificationPerson($notification, $person);
    }

}