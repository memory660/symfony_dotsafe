<?php
// tests/AuthenticationTest.php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class AuthenticationTest extends ApiTestCase
{
    use ReloadDatabaseTrait;


    public function testLogin(): void
    {
        static::bootKernel();

        // (2) use static::getContainer() to access the service container
        $container = self::$kernel->getContainer();

        $client = static::createClient();

        $user = new User();
        $user->setUsername('test');
        $user->setEmail('test@test.fr');
        $user->setRoles(["ROLE_USER"]);        
        $user->setPassword(
            //$container->get(UserPasswordEncoderInterface::class)->hashPassword($user, '$3CR3T')
            '$2y$04$s41UfqOEkYc66BldbxBg0uSNFvbZD1VH1bZTrqIim6WzDLnPaRPFO'
        );

        $manager = $container->get('doctrine')->getManager();
        $manager->persist($user);
        $manager->flush();

        // retrieve a token
        $response = $client->request('POST', 'http://localhost:8001/authentication_token', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'test@test.fr',
                'password' => 'test22',
            ],
        ]);

        $json = $response->toArray();
        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('token', $json);

        // test not authorized
        $client->request('GET', '/api/projects');
        $this->assertResponseStatusCodeSame(401);

        // test authorized
        $client->request('GET', '/api/projects', ['auth_bearer' => $json['token']]);
        $this->assertResponseIsSuccessful();
    }
}