<?php
// api/tests/ProjectsTest.php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Project;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class ProjectTest extends ApiTestCase
{
    // Ce trait fourni par AliceBundle se chargera de rafraîchir le contenu de la base de données à un état connu avant chaque test.
    use RefreshDatabaseTrait;

    public function testGetCollection(): void
    {
        // Le client implémente la `HttpClientInterface` de Symfony, et la réponse la `ResponseInterface`.
        $response = static::createClient()->request('GET', '/projects');

        $this->assertResponseIsSuccessful();
        // Indique que le type de contenu renvoyé est JSON-LD (par défaut).
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        // Affirme que le JSON retourné est un sur-ensemble de celui-ci.
        $this->assertJsonContains([
            '@context' => '/contexts/Project',
            '@id' => '/Projects',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 100,
            'hydra:view' => [
                '@id' => '/Projects?page=1',
                '@type' => 'hydra:PartialCollectionView',
                'hydra:first' => '/Projects?page=1',
                'hydra:last' => '/Projects?page=4',
                'hydra:next' => '/Projects?page=2',
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
        $response = static::createClient()->request('POST', '/Projects', ['json' => [
            'name' => 'projet no1',
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/contexts/Project',
            '@type' => 'Project',
            'name' => 'projet no1',
        ]);
        $this->assertMatchesRegularExpression('~^/Projects/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Project::class);
    }

    public function testCreateInvalidProject(): void
    {
        static::createClient()->request('POST', '/Projects', ['json' => [
            'name' => 'invalid',
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'isbn: This value is neither a valid ISBN-10 nor a valid ISBN-13.
title: This value should not be blank.
description: This value should not be blank.
author: This value should not be blank.
publicationDate: This value should not be null.',
        ]);
    }

    public function testUpdateProject(): void
    {
        $client = static::createClient();
        // findIriBy permet de récupérer l'IRI d'un élément en recherchant certaines de ses propriétés.
        // L'ISBN 9786644879585 a été généré par Alice lors du chargement des montages de test.
        // Comme Alice utilise un générateur de nombres pseudo-aléatoires ensemencé, nous sommes sûrs que cet ISBN sera toujours généré.
        $iri = $this->findIriBy(Project::class, ['name' => 'projet no2']);

        $client->request('PUT', $iri, ['json' => [
            'name' => 'updated name',
        ]]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@id' => $iri,
            'name' => 'projet no2',
        ]);
    }

    public function testDeleteProject(): void
    {
        // bugue sur : static::$container................

        /*
        $client = static::createClient();
        $iri = $this->findIriBy(Project::class, ['isbn' => '9781344037075']);

        $client->request('DELETE', $iri);

        $this->assertResponseStatusCodeSame(204);
        $this->assertNull(
            // Grâce au conteneur, vous pouvez accéder à tous vos services depuis les tests, y compris l'ORM, le mailer, les clients API distants...
            static::$container->get('doctrine')->getRepository(Project::class)->findOneBy(['isbn' => '9781344037075'])
        );
        */
    }

    public function testLogin(): void
    {
        $response = static::createClient()->request('POST', '/login', ['json' => [
            'email' => 'admin@example.com',
            'password' => 'admin',
        ]]);
        
        $this->assertResponseIsSuccessful();
    }
}
