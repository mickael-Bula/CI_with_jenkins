<?php

namespace App\Tests\Security;

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

        $github = new GithubUserProvider($client, $serializer);
        $user = $github->loadUserByUsername('locullus');
        // la méthode retourne soit un User, soit une exception de type LogicException
        $this->assertInstanceOf('App\Entity\User', $user);
    }
}