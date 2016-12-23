<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class NotebookRepository extends EntityRepository
{
    public function find15NotebooksOrderedByPrice()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT n FROM AppBundle:Notebook n ORDER BY n.price ASC'
            )
            ->getResult();
    }
}