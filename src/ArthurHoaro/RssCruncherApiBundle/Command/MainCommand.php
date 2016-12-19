<?php

namespace ArthurHoaro\RssCruncherApiBundle\Command;

use ArthurHoaro\RssCruncherApiBundle\Entity\Feed;
use ArthurHoaro\RssCruncherApiBundle\Entity\UserFeed;
use SimplePMS\Message;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class FeedCreatorCommand
 *
 * This command will fetch a feed after its creation.
 *
 * @package ArthurHoaro\RssCruncherApiBundle\Command
 */
class MainCommand extends ContainerAwareCommand
{
    /**
     * Command info.
     *
     * Called by `rsscruncher:run`.
     */
    protected function configure()
    {
        $this
            ->setName('rsscruncher:run')
            ->setDescription('Run RSSCruncher')
        ;
    }

    /**
     * Execute the command. Infinite loop, reading the update SPMS queue. It will:
     *   - refresh feeds after their creation
     *
     * FIXME! Log events
     *
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $logger = $this->getContainer()->get('logger');
        $queue = $this->getContainer()->get('arthur_hoaro_rss_cruncher_api.queue_manager')->getManager();
        while (true) {

            // Feed update queue
            $messages = $queue->receiveMessages('update');
            foreach ($messages as $message) {
                if (! $message instanceof Message) {
                    continue;
                }

                if ($message->getContent() instanceof UserFeed) {
                    $content = $message->getContent()->getFeed();
                } else {
                    $content = $message->getContent();
                }

                if ($content instanceof Feed) {
                    // update
                    $this->refreshFeed($content);
                    //$queue->deleteMessage($message);
                }
            }
            sleep(1);
        }
    }

    /**
     * Use feed handler to actually refresh a feed, and update its fetch date.
     *
     * @param Feed $feed Feed object extracted from SPMS message.
     */
    protected function refreshFeed(Feed $feed)
    {
        $em = $this->getContainer()->get('doctrine');
        $repo = $em->getRepository(Feed::class);
        $feed = $repo->find($feed->getId());
        $feedHandler = $this->getContainer()->get('arthur_hoaro_rss_cruncher_api.feed.handler');
        $items = $feedHandler->refreshFeed(
            $feed,
            $this->getContainer()->get('simplepie')
        );

        $validator = $this->getContainer()->get('validator');

        foreach ($items as $item) {
            if (count($validator->validate($item)) == 0 ) {
                $this->getContainer()->get('arthur_hoaro_rss_cruncher_api.article.handler')->save($item);
            }
        }
        $feedHandler->updateDateFetch($feed);
    }
}
