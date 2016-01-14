<?php

namespace Opifer\CmsBundle\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Opifer\CmsBundle\Entity\Cron;

/**
 * CronRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CronRepository extends EntityRepository
{
    /**
     * Find all cronjobs which are due.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function findDue()
    {
        $active = $this->createQueryBuilder('c')
            ->where('c.state <> :canceled')
            ->andWhere('c.state <> :running')
            ->orderBy('c.priority', 'DESC')
            ->setParameters([
                'canceled' => Cron::STATE_CANCELED,
                'running' => Cron::STATE_RUNNING,
            ])
            ->getQuery()
            ->getResult()
        ;

        $due = [];
        foreach ($active as $cron) {
            if ($cron->isDue()) {
                $due[] = $cron;
            }
        }

        return new ArrayCollection($due);
    }
}