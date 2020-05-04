<?php

namespace Opifer\MailingListBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Opifer\MailingListBundle\Entity\MailingList;
use Opifer\MailingListBundle\Entity\Subscription;
use Opifer\MailingListBundle\Repository\SubscriptionRepository;

class SubscriptionManager
{
    /** @var EntityManagerInterface */
    protected $em;

    /**
     * Constructor.
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $status
     *
     * @return $this
     */
    public function updateStatus(Subscription $subscription, $status)
    {
        $subscription->setStatus($status);

        if (Subscription::STATUS_SUBSCRIBED == $status) {
            $subscription->setSyncedAt(new \DateTime());
        }

        $this->em->persist($subscription);
        $this->em->flush($subscription);

        return $this;
    }

    /**
     * @param $email
     *
     * @return Subscription|null
     */
    public function findOrCreate(MailingList $list, $email)
    {
        /** @var SubscriptionRepository $repo */
        $repo = $this->em->getRepository('OpiferMailingListBundle:Subscription');

        $subscription = $repo->findInListByEmail($list, $email);

        if (!$subscription) {
            $subscription = new Subscription();
            $subscription->setMailingList($list);
            $subscription->setEmail($email);
        }

        return $subscription;
    }

    /**
     * @return array
     */
    public function findOutOfSync(MailingList $list)
    {
        /** @var SubscriptionRepository $repo */
        $repo = $this->em->getRepository('OpiferMailingListBundle:Subscription');

        return $repo->findInListOutOfSync($list);
    }

    public function save(Subscription $subscription)
    {
        if (!$subscription->getId()) {
            $this->em->persist($subscription);
        }

        $this->em->flush($subscription);
    }

    public function saveList(MailingList $list)
    {
        if (!$list->getId()) {
            $this->em->persist($list);
        }

        $this->em->flush($list);
    }

    public function getRepository()
    {
        return $this->em->getRepository(Subscription::class);
    }
}
