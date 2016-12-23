<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class NotebookRepository extends EntityRepository
{
    public function find15NotebooksOrderedByPrice()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT u FROM AppBundle:Notebook u ORDER BY u.price ASC'
            )
            ->getResult();
    }
}