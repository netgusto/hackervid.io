<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Output\OutputInterface;

use ModelBundle\Entity\User;

class UpdateItemMomentumCommand extends ContainerAwareCommand {
    protected function configure() {
        $this
            ->setName('app:job:momentum')
            ->setDescription('Update items momentum')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $epsilon = 0.0000001;

        $em = $this->getContainer()->get('em');
        $itemrepo = $this->getContainer()->get('itemrepo');
        $rankinghelper = $this->getContainer()->get('rankinghelper');
        $upvotehelper = $this->getContainer()->get('upvotehelper');

        $earliestdate = $upvotehelper->getEarliestItemVoteDate();

        # fetch items submitted in considered period
        $iterator = $itemrepo->qb_active()
            ->andWhere("data.creationdate >= :earliestdate")
                ->setParameter("earliestdate", $earliestdate)
                ->getQuery()
                ->iterate();

        foreach($iterator as $items) {
            $item =& $items[0];

            $prevmomentum = $item->getMomentum();
            $newmomentum = $rankinghelper->computeItemMomentum($item);

            if(abs($prevmomentum - $newmomentum) > $epsilon) {
                $item->setMomentum($newmomentum);
                $itemrepo->update($item, false);    // false: do not flush
                $output->writeLn("[" . $item->getId() . "] " . $item->getTitle() . " : Momentum updated from " . $prevmomentum . " to " . $item->getMomentum());
            }
        }

        $em->flush();

        $output->writeLn("Done.");
    }
}
