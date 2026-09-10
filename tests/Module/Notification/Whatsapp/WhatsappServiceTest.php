<?php

namespace GorillaSoft\Grimlock\Tests\Module\Notification\Whatsapp;

use GorillaSoft\Grimlock\Core\Collection\StringMap;
use GorillaSoft\Grimlock\Core\Exception\CoreException;
use GorillaSoft\Grimlock\Module\Notification\Whatsapp\Dto\Person;
use GorillaSoft\Grimlock\Module\Notification\Whatsapp\Whatsapp;
use GorillaSoft\Grimlock\Module\RestClient\Dto\Response;
use GorillaSoft\Grimlock\Module\RestClient\RestClient;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

class WhatsappServiceTest extends TestCase
{
    private string $accessToken = 'EAAG...test_token';
    private string $phoneNumberId = '100099887766554';

    private function injectMockRestClient(Whatsapp $service, RestClient $mockRestClient): void
    {
        $reflection = new ReflectionClass(Whatsapp::class);
        $property = $reflection->getProperty('restClient');
        $property->setAccessible(true);
        $property->setValue($service, $mockRestClient);
    }

    /**
     * @throws ReflectionException
     */
    private function createServiceWithoutConstructor(): Whatsapp
    {
        $reflection = new ReflectionClass(Whatsapp::class);
        return $reflection->newInstanceWithoutConstructor();
    }

    public function testConstructorThrowsExceptionWhenAccessTokenIsEmpty(): void
    {
        $this->expectException(CoreException::class);
        new Whatsapp('', $this->phoneNumberId);
    }

    public function testConstructorThrowsExceptionWhenPhoneNumberIdIsEmpty(): void
    {
        $this->expectException(CoreException::class);
        new Whatsapp($this->accessToken, '');
    }

    /**
     * @throws CoreException
     * @throws ReflectionException
     */
    public function testSendMessageSuccessAndFormatsPlaceholder(): void
    {
        $service = $this->createServiceWithoutConstructor();

        $mockResponse = $this->createMock(Response::class);
        $mockResponse->code = 200;

        $mockRestClient = $this->createMock(RestClient::class);
        $mockRestClient->expects($this->once())
            ->method('post')
            ->with(
                'messages',
                [
                    'type' => 'text',
                    'to' => '51999888777',
                    'recipient_type' => 'individual',
                    'messaging_product' => 'whatsapp',
                    'text' => [
                        'body' => 'Hello Ruben.'
                    ]
                ]
            )
            ->willReturn($mockResponse);

        $this->injectMockRestClient($service, $mockRestClient);

        $person = new Person('Ruben', '51999888777');

        $placeholders = new StringMap();
        $placeholders->put('name', 'Ruben');

        $result = $service->sendMessage($person, 'Hello :name.', $placeholders);
        $this->assertTrue($result);
    }

    /**
     * @throws CoreException
     * @throws ReflectionException
     */
    public function testSendMessageReturnsFalseWhenHttpCodeIsNot200(): void
    {
        $service = $this->createServiceWithoutConstructor();

        $mockResponse = $this->createMock(Response::class);
        $mockResponse->code = 400;

        $mockRestClient = $this->createMock(RestClient::class);
        $mockRestClient->method('post')->willReturn($mockResponse);

        $this->injectMockRestClient($service, $mockRestClient);

        $person = new Person('Joe Doe', '51999888777');

        $result = $service->sendMessage($person, 'Message Test');
        $this->assertFalse($result);
    }

    /**
     * @throws CoreException
     * @throws ReflectionException
     */
    public function testSendTemplateSuccess(): void
    {
        $service = $this->createServiceWithoutConstructor();

        $mockResponse = $this->createMock(Response::class);
        $mockResponse->code = 200;

        $mockRestClient = $this->createMock(RestClient::class);
        $mockRestClient->expects($this->once())
            ->method('post')
            ->with(
                'messages',
                [
                    'type' => 'template',
                    'to' => '51999888777',
                    'recipient_type' => 'individual',
                    'messaging_product' => 'whatsapp',
                    'template' => [
                        'name' => 'hello_world',
                        'language' => 'es_ES'
                    ]
                ]
            )
            ->willReturn($mockResponse);

        $this->injectMockRestClient($service, $mockRestClient);

        $person = new Person('', '51999888777');

        $result = $service->sendTemplate($person, 'hello_world');
        $this->assertTrue($result);
    }

    /**
     * @throws CoreException
     * @throws ReflectionException
     */
    public function testSendTemplateReturnsFalseWhenHttpCodeIsNot200(): void
    {
        $service = $this->createServiceWithoutConstructor();

        $mockResponse = $this->createMock(Response::class);
        $mockResponse->code = 500;

        $mockRestClient = $this->createMock(RestClient::class);
        $mockRestClient->method('post')->willReturn($mockResponse);

        $this->injectMockRestClient($service, $mockRestClient);

        $person = new Person('', '51999888777');

        $result = $service->sendTemplate($person, 'hello_world');
        $this->assertFalse($result);
    }

}
