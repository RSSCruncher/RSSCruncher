<?php
/**
 * FeedCreatorCommand.php
 * Author: arthur
 */

namespace ArthurHoaro\RssCruncherApiBundle\Command;

use ArthurHoaro\RssCruncherApiBundle\Entity\Feed;
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
    protected function configure()
    {
        $this
            ->setName('rsscruncher:run')
            ->setDescription('Run RSSCruncher')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $logger = $this->getContainer()->get('logger');
        $queue = $this->getContainer()->get('arthur_hoaro_rss_cruncher_api.queue_manager')->getManager();
        while (true) {

            // Feed update queue
            $messages = $queue->receiveMessages('update');
            error_log(var_export($messages, true));
            foreach ($messages as $message) {
                if ($message instanceof Message && $message->getContent() instanceof Feed) {
                    // update
                    $this->refreshFeed($message->getContent());
                    $queue->deleteMessage($message);
                }
            }
            sleep(1);
        }
    }

    protected function refreshFeed(Feed $feed)
    {
        $items = $this->getContainer()->get('arthur_hoaro_rss_cruncher_api.feed.handler')->refreshFeed(
            $feed->getId(),
            $this->getContainer()->get('feedio')
        );

        $validator = $this->getContainer()->get('validator');

        foreach($items as $item) {
            if( count($validator->validate($item)) == 0 ) {
                $articles[] = $this->getContainer()->get('arthur_hoaro_rss_cruncher_api.article.handler')->save($item);
            }
        }
    }
}