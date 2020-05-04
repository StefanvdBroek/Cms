<?php

namespace Opifer\MailingListBundle\Command;

use Opifer\MailingListBundle\Entity\MailingList;
use Opifer\MailingListBundle\Entity\Subscription;
use Opifer\MailingListBundle\Provider\MailingListProviderInterface;
use Opifer\MailingListBundle\Provider\MailPlusProvider;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncSubscriptionCommand extends ContainerAwareCommand
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var ProgressBar */
    protected $progress;

    protected function configure()
    {
        $this->setName('opifer:sync:subscriptions')
            ->setDescription('Synchronize mailinglist subscriptions');
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getEntityManager();

        if (class_exists('Symfony\\Component\\Console\\Helper\\ProgressBar')) {
            ProgressBar::setFormatDefinition('normal', " %current%/%max% [%bar%] %percent:3s%%\n%message%");
            ProgressBar::setFormatDefinition('verbose', " %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%\n%message%");
            ProgressBar::setFormatDefinition('very_verbose', " %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s%\n%message%");
            ProgressBar::setFormatDefinition('debug', " %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%\n%message%");
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $providerPool = $this->getContainer()->get('opifer.mailinglist.provider_pool');

        $lists = $this->em->getRepository('OpiferMailingListBundle:MailingList')->findWithProviders();

        if (empty($lists)) {
            $output->writeln('<info>0 lists found to synchronise: exiting…</info>');

            return;
        }

        $logger = $this->buildLoggerClosure($output, count($lists));
        $logger('Lists initialised.');

        foreach ($lists as $list) {
            /** @var MailingListProviderInterface $provider */
            $provider = $providerPool->getProvider($list->getProvider());

            $provider->synchroniseList($list, $logger);

            $logger(sprintf('Finished synchronisation on %s with %s', $list->getName(), $provider->getName()), 1);
        }

//
//            /** @var MailingList $mailingList */
//            foreach ($mailingLists as $mailingList) {
//                if ($mailingList->getProvider() == 'mailplus') {
//                    $output->writeln(sprintf('Synchronizing subscriptions for mailinglist %s', $mailingList->getDisplayName()));
//
//                    /** @var MailPlusProvider $provider */
//                    $provider = $this->getContainer()->get('opifer.mailplus_provider');
//
//                    $synced = $failed = 0;
//
//                    $subscriptions = $subscriptionRepository->getUnsyncedByMailinglist($mailingList);
//
//                    /** @var Subscription $subscription */
//                    foreach ($subscriptions as $subscription) {
//                        $success = $provider->sync($subscription);
//
//                        if ($success) {
//                            $synced++;
//                        } else {
//                            $failed++;
//                        }
//                    }
//
//                    $output->writeln(sprintf('%d synched and %d failed of %d subscriptions', $synced, $failed, count($subscriptions)));
//                }
//            }
//        }
    }

    public function buildLoggerClosure(OutputInterface $output, $total)
    {
        $progress = null;

        return function ($message, $increment = null) use (&$progress, $output, $total) {
            if (null === $progress) {
                $progress = new ProgressBar($output, $total);
                $progress->setFormat('debug');
                $progress->start();
            }

            $progress->setMessage(sprintf('<info>%s</info>', $message));

            (null == $increment) ? $progress->display() : $progress->advance($increment);
        };
    }

    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine');
    }
}
