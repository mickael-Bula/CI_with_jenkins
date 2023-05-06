<?php

namespace App\Tests\Security;

use App\Entity\User;
use App\Security\GithubUserProvider;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class GithubUserProviderTest extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testLoadUserByUsername()
    {
        // le $client est une instance de la classe GuzzleHttp\Client
        // le $client étant produit par le constructeur, je dois désactiver celui-ci
        $client = $this
            ->getMockBuilder('GuzzleHttp\Client')
            ->disableOriginalConstructor()
            ->getMock();

        // je fais de même avec le serializer instance de JMS\Serializer\SerializerInterface
        $serializer = $this
            ->getMockBuilder('JMS\Serializer\SerializerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $response = $this
            ->getMockBuilder('Psr\Http\Message\ResponseInterface')
            ->disableOriginalConstructor()
            ->getMock();

        // je m'assure du retour de la méthode get() du $client
        $client
            ->method('get')
            ->willReturn($response);

        $response2 = $this
            ->getMockBuilder('Psr\Http\Message\StreamInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $response
            ->method('getBody')
            ->willReturn($response2);

        $response2
            ->method('getContents')
            ->willReturn('foo');

        $userData = [
            'login' => 'a login',
            'name' => 'user name',
            'email' => 'adress@mail.com',
            'avatar_url' => 'url to the avatar',
            'html_url' => 'url to profile'
        ];

        $serializer
            ->method('deserialize')
            ->willReturn($userData);

        /**
         * expectations
         */
        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($response2);

        $response2
            ->expects($this->once())
            ->method('getContents')
            ->willReturn('foo');

        $client
            ->expects($this->once())
            ->method('get')
            ->willReturn($response);

        $serializer
            ->expects($this->once())
            ->method('deserialize')
            ->willReturn($userData);

        $github = new GithubUserProvider($client, $serializer);
        $user = $github->loadUserByUsername('locullus');

        $expectedUser = new User(
            $userData['login'],
            $userData['name'],
            $userData['email'],
            $userData['avatar_url'],
            $userData['html_url']
        );

        $this->assertEquals($expectedUser, $user);
        $this->assertEquals('App\Entity\User', get_class($user));
    }

    /**
     * @throws GuzzleException
     */
    public function testLoadUserByUsernameThrowingException()
    {
        // il n'est pas nécessaire de désactiver le constructeur : de simples mocks font l'affaire
        $client = $this->createMock('GuzzleHttp\Client');
        $serializer = $this->createMock('JMS\Serializer\SerializerInterface');
        $response = $this->createMock('Psr\Http\Message\ResponseInterface');
        $response2 = $this->createMock('Psr\Http\Message\StreamInterface');

        // on peut partir du principe qu'il faut définir le retour de toutes les méthodes rencontrées dans le code à tester
        $client
            ->method('get')
            ->willReturn($response);

        $response
            ->method('getBody')
            ->willReturn($response2);

        $response2
            ->method('getContents')
            ->willReturn('foo');

        $serializer
            ->method('deserialize')
            ->willReturn(null);

        $this->expectException('logicException');

        // la levée de l'exception faisant sortir de la méthode testée, ce qui semble ne pas être évalué par le test
        $github = new GithubUserProvider($client, $serializer);
        $user = $github->loadUserByUsername('locullus');

        $this->assertEquals('App\Entity\User', get_class($user));
    }
}