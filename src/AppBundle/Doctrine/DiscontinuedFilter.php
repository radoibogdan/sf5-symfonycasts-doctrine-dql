<?php

namespace AppBundle\Doctrine;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

/**
 * Si Activé sera appelé pour tous les queries
 */
class DiscontinuedFilter extends SQLFilter
{

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
       # Appliquer le filtre que pour la classe FortuneCookie, le return '' est important
        if ($targetEntity->getReflectionClass()->name != 'AppBundle\Entity\FortuneCookie') {
            return ''; # Ne rien faire
        }

        # targetTableAlias c'est l'alias "cat" dans le repo "$this->createQueryBuilder('cat')"
//        return sprintf('%s.discontinued = false', $targetTableAlias);

        # Voir FortuneController/homepageAction pour le parametre
        return sprintf('%s.discontinued = %s', $targetTableAlias, $this->getParameter('discontinued'));
    }
}