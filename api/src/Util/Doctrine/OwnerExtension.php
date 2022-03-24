<?php

declare(strict_types=1);

namespace App\Util\Doctrine;


use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Subject;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class OwnerExtension implements QueryCollectionExtensionInterface
{

    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null
    ) {
        $token = $this->tokenStorage->getToken();
        if ($token === null) {
            return;
        }
        $user = $token->getUser();
        if (!$user instanceof User) {
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];
        switch ($resourceClass) {
            case Subject::class:
                $queryBuilder->andWhere(sprintf('%s.user = :current_user', $rootAlias));
                break;
            default:
                return;
        }

        $queryBuilder->setParameter('current_user', $user->getId());
    }
}
