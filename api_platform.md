

api_platform.yaml


# vs code

## extensions

```
php intelephense
php namespace resolvers
twig langage 2
```

## erreur undefined VS CODE

F1   developper:reload  window

## config

```
CTRL + ,

suggest basic :  décocher [ ] controle si les langages...
format save :  cocher [*] format on save
emmet include : ajouter l'element -> [twig]    [html]
```

# command Symfony

```
composer create-project symfony/skeleton bookshop-api
composer require api
composer require symfony/maker-bundle --dev
composer require jwt-auth

php bin/console doctrine:database:create
php bin/console doctrine:schema:create 

php -S 127.0.0.1:8000 -t public
https://localhost/docs/

composer require --dev symfony/maker-bundle
php bin/console make:entity --api-resource 
php bin/console make:crud 
php bin/console make:controller

php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate

php bin/console debug:autowiring ...   (security, authentication, ...)


php bin/console debug:router
php bin/console cache:clear
php bin/console make:user --no-debug
php bin/console lexik:generate-keypair
```

```
openssl genrsa -out private.pem 4096
openssl rsa -in mykey.pem -pubout -out public.pem
```

```
composer require ramsey/uuid-doctrine
```

```
    #[ApiSubresource()]    

```

# entity

```php
use ApiPlatform\Core\Annotation\ApiResource;

#[ApiResource]
class Product {

    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    #[ORM\Column(nullable: true)] 
    #[ORM\Column]
    #[ORM\Column(type: 'smallint')]   
    #[ORM\Column(type: 'text')]

    #[ORM\ManyToOne(inverdedBy: 'reviews')] 
    #[Assert\NotNull]
    public ?Book $book = null;          
```

# DataPersisters

- effectuer une tache avant qu'il soit persister en base de données
- par exemple, remplir un champs : ```created_at``` avec la date du moment

# Validating Data

```php
use Symfony\Component\Validator\Constraints as Assert;

 #[Assert\Isbn]
 #[Assert\NotBlank]
 #[Assert\NotNull]
 #[Assert\Range(min: 0, max: 5)]
```

# relation

```php
class Product {

    #[ORM\OneToMany(targetEntity: Offer::class, mappedBy: 'product', cascade: ['persist'])] 
    public iterable $offers;

    public function __construct()
    {
        $this->offers = new ArrayCollection(); 
    }    
```

```php
class Offer {

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'offers')]
    public ?Product $product = null;    
```

# Extending API Platform

```
Data Providers	                adaptateurs pour couches de persistance personnalisées, champs virtuels, hydratation personnalisée
Denormalizers	                des objets de post-traitement créés à partir de la charge utile (payload) envoyée dans le corps de la requête HTTP
Voters	                        custom authorization logic
Validation constraints	        custom validation logic
Data Persisters	                custom business logic and computations to trigger before or after persistence (ex: mail, call to an external API...)
                                des calculs personnalisés à déclencher avant ou après la persistance
Normalizers	                    personnaliser la ressource envoyée au client (add fields in JSON documents, encode codes, dates...)
Filters	                        create filters for collections and automatically document them (OpenAPI, GraphQL, Hydra)
Serializer Context Builders	    Changing the Serialization context (e.g. groups) dynamically
Messenger Handlers	            create 100% custom, RPC, async, service-oriented endpoints (should be used in place of custom controllers because the messenger integration
                                is compatible with both REST and GraphQL, while custom controllers only work with REST)
DTOs and Data Transformers	    utiliser une classe spécifique pour représenter la structure de données d'entrée ou de sortie liée à une opération
Kernel Events	                personnaliser la demande ou la réponse HTTP (REST uniquement, les autres points d'extension doivent être privilégiés lorsque cela 
                                est possible)
Subresources                                                    
```

# Operations

## Enabling and Disabling Operations

```php
use ApiPlatform\Core\Annotation\ApiResource;

#[ApiResource(
    collectionOperations: ['get'],
    itemOperations: ['get'],
)]
class Book {
```

- Si vous ne voulez pas autoriser l'accès à l'élément de ressource (c'est-à-dire que vous ne voulez pas d'opération GET item), au lieu de l'omettre complètement, vous devriez déclarer une opération GET item qui renvoie HTTP 404 (Not Found), afin que l'élément de ressource puisse toujours être identifié par un IRI :

```php
#[ApiResource(
    collectionOperations: [
        'get' => ['method' => 'get'],
    ],
    itemOperations: [
        'get' => [
            'controller' => NotFoundAction::class,
            'read' => false,
            'output' => false,
        ],
    ],
)]
class Book {
```    

## Configuring Operations

```php
#[ApiResource(
    collectionOperations: [
        'post' => [
            'path' => '/grimoire',
            'status' => 301,
        ],
    ],
    itemOperations: [
        'get' => [
            'path' => '/grimoire/{id}',
            'requirements' => ['id' => '\d+'],
            'defaults' => ['color' => 'brown'],
            'options' => ['my_option' => 'my_option_value'],
            'schemes' => ['https'],
            'host' => '{subdomain}.api-platform.com',
        ],
    ],
)]
class Book {
```

## Prefixing All Routes of All Operations

- .../library/book

```php
#[ApiResource(routePrefix: '/library')]
class Book {
```

## Expose a Model Without Any Routes

- Il peut arriver que vous souhaitiez exposer un modèle, mais que vous souhaitiez qu'il soit utilisé uniquement par le biais de sous-requêtes, et jamais par le biais d'opérations sur des éléments ou des collections
- Puisque la norme OpenAPI exige qu'au moins une route soit exposée 
- De cette façon, nous exposons une route qui ne fera... rien. Notez que le contrôleur n'a même pas besoin d'exister.

```php
#[ApiResource(
    itemOperations: [
        'get' => [
            'method' => 'GET',
            'controller' => SomeRandomController::class,
        ],
    ],
)]
class Weather {
```

- il ne reste qu'un dernier problème : notre fausse opération d'article est visible dans la documentation de l'API. 
- Pour la supprimer, nous devons décorer la documentation Swagger :

```php
<?php
// src/OpenApi/OpenApiFactory.php

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\Core\OpenApi\Model;

final class OpenApiFactory implements OpenApiFactoryInterface {
```

# Data Providers : vous souhaitez parfois récupérer des données à partir d'autres sources

- https://api-platform.com/docs/core/data-providers/#custom-collection-data-provider

- Un fournisseur de données utilisant Doctrine ORM pour récupérer les données d'une base de données (activé par défaut)
- un fournisseur de données utilisant Doctrine MongoDB ODM pour récupérer les données d'une base de données de documents, 
- un fournisseur de données utilisant Elasticsearch-PHP pour récupérer les données d'un cluster Elasticsearch sont inclus dans la bibliothèque. 

- Cependant, vous souhaitez parfois récupérer des données à partir d'autres sources telles qu'une autre couche de persistance ou un webservice. 
- Les fournisseurs de données personnalisés peuvent être utilisés à cette fin. 
- Un projet peut inclure autant de fournisseurs de données que nécessaire. 
- Le premier capable de récupérer des données pour une ressource donnée sera utilisé.

- l'interface ```CollectionDataProviderInterface``` est utilisée lors de la récupération d'une collection.
- l'interface ```ItemDataProviderInterface``` est utilisée pour récupérer des éléments.
- l'interface ```RestrictedDataProviderInterface``` si vous souhaitez limiter leurs effets à une seule ressource ou opération.

- Injecting the ```Serializer``` in an ```ItemDataProvider```
- Injecting Extensions (```Pagination, Filter, EagerLoading``` etc.)

# Data Persisters : des calculs personnalisés à déclencher avant ou après la persistance

- https://api-platform.com/docs/core/data-persisters/#data-persisters

# Filters : appliquer des filtres et des critères de tri sur les collections

- https://api-platform.com/docs/core/filters/#filters

- API Platform Core fournit un système générique pour appliquer des filtres et des critères de tri sur les collections. 
- Des filtres utiles pour Doctrine ORM, MongoDB ODM et ElasticSearch sont fournis avec la bibliothèque.
- Vous pouvez également créer des filtres personnalisés qui répondent à vos besoins spécifiques. 
- Vous pouvez également ajouter un support de filtrage à vos fournisseurs de données personnalisés en implémentant les interfaces fournies par la bibliothèque.
- Par défaut, tous les filtres sont désactivés



# Subresources : est une collection ou un élément qui appartient à une autre ressource

- https://api-platform.com/docs/core/subresources/#subresources


- Le point de départ d'une sous-ressource doit être une relation sur une ressource existante. 
- Par exemple, créons deux entités (Question, Réponse) et mettons en place une sous-ressource pour que /question/42/answer nous donne la réponse à la question 42 

```php
    #[ORM\OneToOne]
    #[ORM\JoinColumn(referencedColumnName: 'id', unique: true)]
    #[ApiSubresource]
    public Answer $answer;
```

## Using Serialization Groups

```php
 #[ApiResource(
    subresourceOperations: [
        'api_questions_answer_get_subresource' => [
            'method' => 'GET',
            'normalization_context' => [
                'groups' => ['foobar'],
            ],
        ],
    ],
)]
class Answer {
```

## Using Custom Paths

```php
#[ApiResource(
    subresourceOperations: [
        'api_questions_answer_get_subresource' => [
            'method' => 'GET',
            'path' => '/questions/{id}/all-answers',
        ],
    ],
)]
class Question{
```

## Access Control of Subresources


```php
 #[ApiResource(
    subresourceOperations: [
        'api_questions_answer_get_subresource' => [
            'security' => "is_granted('ROLE_AUTHENTICATED')",
        ],
    ],
 )]
 class Answer {
```

## Limiting Depth


```php
#[ApiResource]
class Question
{
    #[ApiSubresource(
        maxDepth: 1,
    )]
    public $answer;
```

# The Serialization Process : Serializer pour transformer les entités PHP en réponses API 

## Overall Process

## The Serialization Context, Groups and Relations

## Configuration

## Using Serialization Groups

- normalizationContext : GET 
- denormalizationContext : PUT (toutes les propriétés) / PATCH (propriétés partielles) / POST

```php
#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
)]
class Book
{
    #[Groups(["read", "write"])]
    public $name;

    #[Groups("write")]
    public $author;
```

## Using Serialization Groups per Operation

- Il est possible de spécifier les contextes de normalisation et de dénormalisation (ainsi que tout autre attribut) sur une base par opération. La plate-forme API utilisera toujours la définition la plus spécifique. 
- Par exemple, si des groupes de normalisation sont définis à la fois au niveau de la ressource et au niveau de l'opération, la configuration définie au niveau de l'opération sera utilisée et le niveau de la ressource sera ignoré.


```php
#[ApiResource(
    normalizationContext: ['groups' => ['get']],
    itemOperations: [
        'get',
        'put' => [
            'normalization_context' => ['groups' => ['put']],
        ],
    ],
)]
class Book
{
    #[Groups(["get", "put"])]
    public $name;

    #[Groups("get")]
    public $author;
```

## Operations

- Par défaut, pour toutes les ressources, 5 points de terminaison de base sont créés.
- Ils sont regroupés en deux types d'opérations. 
    - Le premier groupe concerne les opérations effectuées sur une collection d'objets ou la création d'un nouvel élément. 
    - Un autre type d'opérations est toujours lié à un certain élément (et il est nécessaire d'indiquer cet élément).


```php
#[ApiResource(
    collectionOperations: [
        'get',      // get a list of elements
        'post',     // create new element
    ],
    itemOperations: [
        'get',      // get specific element
        'put',      // update element 
        'delete'    // delete element
    ],
)]
```

- exemple : Dans notre application, l'utilisateur ne peut pas supprimer les leçons


```php
#[ApiResource(
    normalizationContext: ['groups' => ['subjectList']],
    denormalizationContext: ['groups' => ['subjectCreate']],    
    itemOperations: [
        'get',      // get specific element
        'put',      // update element 
    ],
)]
class Lesson {
```

- exemple :
    - Notre API doit permettre à l'utilisateur de modifier son mot de passe. 
    - Nous voulons créer l'URL suivante : /users/{id}/change-password. Et l'utilisateur doit fournir uniquement son nouveau mot de passe. 
    - Pour cela, nous n'avons pas besoin d'écrire une nouvelle action dans un contrôleur. 
    - Il suffit de créer une nouvelle opération dans API Platform et de l'informer que pour une certaine opération, le groupe de dénormalisation doit avoir une valeur qui est seulement un champ qui peut être fourni avec un nouveau mot de passe. 
    - Il est nécessaire de veiller à ce qu'un mot de passe soit suffisamment fort. Nous pouvons y parvenir en utilisant la validation.

```php
#[ApiResource(
    normalizationContext: ['groups' => ['userList']],
    denormalizationContext: ['groups' => ['userCreate']],    
    itemOperations: [
        'get',
        'put',        
        'changePassword' => [
            'method' => 'PUT',
            'path' => '/users/{id}/change-password',            
            'denormalization_context' => ['groups' => ['userChangePassword']],
            'validation_groups' => ['userChangePassword'],
            'swagger_context'=> [
                'summary' => ['Change user password']
            ]
        ],
    ],
)]
class User implements UserInterface {
    ...
    #[Groups(["userChangePassword", "userCreate"])]    
    #[Assert\NotBlank(groups: ["userCreate", "userChangePassword"])]        
    #[Assert\Length(min: 8, max: 255, groups: ["userCreate", "userChangePassword"])]     
    private $plainPassword;
    ...
```

# Nested links : créer un nouveau point d'entrée avec des sous relation

- Il s'agit de marquer les relations avec le paramètre ApiSubresource.
- on obtient un point d'entrée : ```GET /api/lessons/1/flashcards```

```php
use ApiPlatform\Core\Annotation\ApiSubresource;

class Lesson {
...
    #[ORM\OneToMany(mappedBy: 'lesson', targetEntity: Flashcard::class, cascade: ["persist", "remove"])]
    #[ApiSubresource]
    private $flashcards;
```

# Searching

- ```search filter```: enables searching through strings (exact, partial, starting with a certain phrase or word),
- ```date filter```: enables searching through a date (or before/after a date),
- ```boolean filter```: enables searching through boolean fields,
- ```range filter```: enables searching through numbers (higher, lower, in between),
- ```exits filter```: enables searching if a field has any value (if it’s not “null”),
- ```order filter```: enables sorting a list of results through a provided field.


# security

```php
 #[ORM\Entity]
 #[ApiResource(
    attributes: ["security" => "is_granted('ROLE_USER')"],
    collectionOperations: [
        "get",
        "post" => ["security" => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        "get",
        "put" => ["security" => "is_granted('ROLE_ADMIN') or object.owner == user"],
    ],
)]
class Book {
...

    #[ORM\ManyToOne]
    public User $owner;
```

## Voters

```php
<?php

namespace App\Security\Voter;

use App\Entity\Annonces;
use App\Entity\Users;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;

class AnnoncesVoter extends Voter
{
    const ANNONCE_EDIT = 'annonce_edit';
    const ANNONCE_DELETE = 'annonce_delete';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $annonce)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::ANNONCE_EDIT, self::ANNONCE_DELETE])
            && $annonce instanceof \App\Entity\Annonces;
    }

    protected function voteOnAttribute($attribute, $annonce, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // On vérifie si l'utilisateur est admin
        if($this->security->isGranted('ROLE_ADMIN')) return true;

        // On vérifie si l'annonce a un propriétaire
        if(null === $annonce->getUsers()) return false;

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::ANNONCE_EDIT:
                // on vérifie si on peut éditer
                return $this->canEdit($annonce, $user);
                break;
            case self::ANNONCE_DELETE:
                // on vérifie si on peut supprimer
                return $this->canDelete();
                break;
        }

        return false;
    }

    private function canEdit(Annonces $annonce, Users $user){
        // Le propriétaire de l'annonce peut la modifier
        return $user === $annonce->getUsers();
    }

    private function canDelete(){
        // Le propriétaire de l'annonce peut la supprimer
        if($this->security->isGranted('ROLE_EDITOR')) return true;
        return false;
    }

}
```



```php

```
```php

```
```php

```
```php

```
```php

```
```php

```
```php

```
```php

```
```php

```