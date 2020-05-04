<?php

namespace Opifer\MailingListBundle\Provider;

use Opifer\MailingListBundle\Entity\MailingList;
use Opifer\MailingListBundle\Entity\Subscription;

/**
 * MailingListProviderInterface is the interface implemented by all provider classes.
 */
interface MailingListProviderInterface
{
    /**
     * Returns the human readable name of the provider.
     *
     * @return string
     */
    public function getName();

    /**
     * Sync a subscription.
     *
     * @return string
     */
    public function synchronise(Subscription $subscription);

    /**
     * Synchronise a with the remote mailing list.
     */
    public function synchroniseList(MailingList $list, \Closure $logger);

    /**
     * Returns an array id, name of remote lists.
     *
     * @return array
     */
    public function getRemoteLists();
}
