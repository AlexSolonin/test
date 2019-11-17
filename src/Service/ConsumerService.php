<?php


namespace App\Service;


use Doctrine\ORM\EntityManagerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

class ConsumerService
{

    private $em;

    private $logger;


    public function __construct(
        EntityManagerInterface $em,
        LoggerInterface $logger
    )
    {
        $this->em = $em;

        $this->logger = $logger;
    }

    public function execute(AMQPMessage $msg)
    {
        try {
            if (!$this->em->getConnection()->isConnected()) {
                $this->em->getConnection()->connect();
            }

            $data = $msg->getBody();
            $userId = json_decode($data, 1);
            $leadService = new GiftService($this->em);
            $leadService->selectGift($userId);
            $this->em->clear();
            $this->em->getConnection()->close();
            gc_collect_cycles();
        } catch (\Exception $e)
        {
            $this->logger->info('consumer info: ' . $e->getMessage());
        }
        return true;
    }

}