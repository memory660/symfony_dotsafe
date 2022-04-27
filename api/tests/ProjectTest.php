<?php
// api/tests/ProjectsTest.php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Project;
use App\Entity\User;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class ProjectTest extends ApiTestCase
{
    // Ce trait fourni par AliceBundle se chargera de rafraîchir le contenu de la base de données à un état connu avant chaque test.
    use RefreshDatabaseTrait;

    protected static $initialized = FALSE;
    protected static $token;

    public function setUp(): void {
        if (!self::$initialized) {        
dump('--- setUp() -----')        ;


        static::bootKernel();
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
        self::$token = $json['token'];
        self::$initialized = TRUE;
    }
    }

// ['auth_bearer' => $json['token']];

    public function testGetCollection(): void
    {
        //
        //
        //
        dump('------------------------------');
        dump(self::$token);
        // Le client implémente la `HttpClientInterface` de Symfony, et la réponse la `ResponseInterface`.
        $response = static::createClient()->request('GET', '/api/projects', ['auth_bearer' => self::$token]);

        $this->assertResponseIsSuccessful();
        // Indique que le type de contenu renvoyé est JSON-LD (par défaut).
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        // Affirme que le JSON retourné est un sur-ensemble de celui-ci.
        $this->assertJsonContains([
            '@context' => '/api/contexts/Project',
            '@id' => '/api/projects',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 100,
            'hydra:view' => [
                '@id' => '/api/projects?page=1',
                '@type' => 'hydra:PartialCollectionView',
                'hydra:first' => '/api/projects?page=1',
                'hydra:last' => '/api/projects?page=4',
                'hydra:next' => '/api/projects?page=2',
            ],
        ]);

        // Comme les tests sont automatiquement chargés entre chaque test, vous pouvez effectuer des assertions sur eux.
        $this->assertCount(30, $response->toArray()['hydra:member']);

        // Affirme que le JSON renvoyé est validé par le schéma JSON généré pour cette ressource par la plateforme API.
        // Ce schéma JSON généré est également utilisé dans la spécification OpenAPI !
        $this->assertMatchesResourceCollectionJsonSchema(Project::class);
    }

    public function testCreateProject(): void
    {
        dump('------------------------------');
        dump(self::$token);        
        //$this->initToken();
        //
        //
        $response = static::createClient()->request('POST', '/api/projects', ['json' => [
            'name' => 'projet no1',
            'auth_bearer' => self::$token            
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Project',
            '@type' => 'Project',
            'name' => 'projet no1',
        ]);
        $this->assertMatchesRegularExpression('~^/api/projects/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Project::class);
    }

    public function testCreateInvalidProject(): void
    {
        dump('------------------------------');
        dump(self::$token);        
        //$this->initToken();
        //
        //        
        static::createClient()->request('POST', '/api/projects', ['json' => [
            'name' => '',
            'auth_bearer' => self::$token  
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/api/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'name: This value should not be blank.',
        ]);
    }    

    public function testUpdateProject(): void
    {
        dump('------------------------------');
        dump(self::$token);        
        //$this->initToken();
        //
        //        
        $client = static::createClient();
        static::createClient()->request('POST', '/api/projects', ['json' => [
            'name' => 'projet no1',
            'auth_bearer' => self::$token  
        ]]);        
        // findIriBy permet de récupérer l'IRI d'un élément en recherchant certaines de ses propriétés.
        // L'ISBN 9786644879585 a été généré par Alice lors du chargement des montages de test.
        // Comme Alice utilise un générateur de nombres pseudo-aléatoires ensemencé, nous sommes sûrs que cet ISBN sera toujours généré.
        $iri = static::findIriBy(Project::class, ['name' => 'projet no1']);

        $client->request('PUT', $iri, ['json' => [
            'name' => 'updated name',
            'auth_bearer' => self::$token  
        ]]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@id' => $iri,
            'name' => 'projet no1',
        ]);
    }    

    public function testDeleteProject(): void
    {
        dump('------------------------------');
        dump(self::$token);        
        //$this->initToken();
        //
        //        
        $client = static::createClient();
        static::createClient()->request('POST', '/api/projects', ['json' => [
            'name' => 'projet no2',
            'auth_bearer' => self::$token  
        ]]); 

        $iri = static::findIriBy(Project::class, ['name' => 'projet no2']);

        $client->request('DELETE', $iri);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@id' => $iri,
            '@context' => '/api/contexts/Project',
            '@type' => 'Project',            
            'name' => 'projet no2',
        ]);
        
    }    
}
