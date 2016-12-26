<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class NotebookRepository
 * @package AppBundle\Repository
 */
class NotebookRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function find15NotebooksOrderedByPrice()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT u FROM AppBundle:Notebook u ORDER BY u.price ASC '
            )
            ->setMaxResults(15)
            ->getResult();
    }
}
