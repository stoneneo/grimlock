<?php

namespace GorillaSoft\Grimlock\Tests\Module\Notification\Firebase;

use Exception;
use GorillaSoft\Grimlock\Core\Exception\CoreException;
use GorillaSoft\Grimlock\Module\Notification\Firebase\Dto\Notification;
use GorillaSoft\Grimlock\Module\Notification\Firebase\Dto\Person;
use GorillaSoft\Grimlock\Module\Notification\Firebase\Firebase;
use GorillaSoft\Grimlock\Module\RestClient\Dto\Response;
use GorillaSoft\Grimlock\Module\RestClient\RestClient;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

class FirebaseServiceTest extends TestCase
{
    private string $firebaseProject = 'test-firebase-project';
    private string $firebaseKey = 'credentials.json';


    private function injectMockRestClient(Firebase $service, RestClient $mockRestClient): void
    {
        $reflection = new ReflectionClass(Firebase::class);
        $property = $reflection->getProperty('restClient');
        $property->setAccessible(true);
        $property->setValue($service, $mockRestClient);
    }

    /**
     * @throws ReflectionException
     */
    private function createServiceWithoutConstructor(): Firebase
    {
        $reflection = new ReflectionClass(Firebase::class);
        $service = $reflection->newInstanceWithoutConstructor();

        $projectProp = $reflection->getProperty('firebaseProject');
        $projectProp->setAccessible(true);
        $projectProp->setValue($service, $this->firebaseProject);

        return $service;
    }

    public function testConstructorThrowsExceptionWhenProjectIsEmpty(): void
    {
        $this->expectException(CoreException::class);
        new Firebase('', $this->firebaseKey);
    }

    public function testConstructorThrowsExceptionWhenKeyIsEmpty(): void
    {
        $this->expectException(CoreException::class);
        new Firebase($this->firebaseProject, '');
    }

    /**
     * @throws ReflectionException
     * @throws CoreException
     */
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

        $notification = new Notification('Title', 'News', 'news', 'https://image.com/news.jpg');

        $result = $service->sendNotification($notification);
        $this->assertTrue($result);
    }

    public function testSendNotificationThrowsExceptionWhenTitleIsEmpty(): void
    {
        $service = $this->createServiceWithoutConstructor();

        $notification = new Notification('');

        $this->expectException(CoreException::class);
        $service->sendNotification($notification);
    }

    /**
     * @throws ReflectionException
     */
    public function testSendNotificationThrowsCoreExceptionOnRestClientError(): void
    {
        $service = $this->createServiceWithoutConstructor();

        $mockRestClient = $this->createMock(RestClient::class);
        $mockRestClient->method('post')
            ->willThrowException(new Exception("Error Conection HTTP"));

        $this->injectMockRestClient($service, $mockRestClient);

        $notification = new Notification('Title', '', '');

        $this->expectException(CoreException::class);
        $service->sendNotification($notification);
    }

    /**
     * @throws ReflectionException
     * @throws CoreException
     */
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

        $notification = new Notification('Welcome', 'Hello Joe Doe', '', 'https://image.com/avatar.png');
        $person = new Person('Joe', 'Doe', 'fcm_registration_token_123');

        $result = $service->sendNotificationPerson($notification, $person);
        $this->assertTrue($result);
    }

    /**
     * @throws ReflectionException
     */
    public function testSendNotificationPersonThrowsExceptionWhenTitleIsNull(): void
    {
        $service = $this->createServiceWithoutConstructor();

        $notification = new Notification('');

        $person = new Person();

        $this->expectException(CoreException::class);
        $service->sendNotificationPerson($notification, $person);
    }

}
