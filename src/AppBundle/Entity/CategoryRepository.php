<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends EntityRepository
{
    public function findAllOrdered()
    {
        # $dql = 'SELECT cat FROM AppBundle\Entity\Category cat ORDER BY cat.name DESC';
        # $query = $this->getEntityManager()->createQuery($dql);

        # Récupère le sql
        # var_dump($query->getSQL());

        $qb = $this->createQueryBuilder('cat')
            ->leftJoin('cat.fortuneCookies', 'fc')
            ->addSelect('fc')
            ->addOrderBy('cat.name', 'DESC');
        $query = $qb->getQuery();

        # Récupère le dql créé par le query builder
        # var_dump($query->getDQL());

        return $query->execute();
    }

    public function search($term)
    {
        # DO NOT USE orWhere
        return $this->createQueryBuilder('cat')
            ->andWhere('cat.name LIKE :searchTerm 
                OR cat.iconKey LIKE :searchTerm
                OR fc.fortune LIKE :searchTerm')
            ->leftJoin('cat.fortuneCookies', 'fc')
            ->addSelect('fc')
            ->setParameter('searchTerm', '%'.$term.'%')
            ->getQuery()
            ->execute();
    }

    public function findWithFortunesJoin(int $id)
    {
        return $this->createQueryBuilder('cat')
            ->leftJoin('cat.fortuneCookies', 'fc')
            ->andWhere('cat.id = :id')
            ->addSelect('fc')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
