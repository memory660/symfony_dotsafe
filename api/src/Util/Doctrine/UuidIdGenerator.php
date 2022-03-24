<?php

namespace App\Util\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;

class UuidIdGenerator extends AbstractIdGenerator
{
    /**
     * Generates an identifier for an entity.
     *
     * @param EntityManager $em
     * @param object|null $entity
     * @return mixed
     */
    public function generate(EntityManager $em, $entity)
    {
        if ($entity->getId() !== null) {
            return $entity->getId();
        }

        return \Ramsey\Uuid\Uuid::uuid4();
    }
}
