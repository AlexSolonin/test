<?php


namespace App\Command;

use App\Service\TransferService;
use Symfony\Component\Console\Command\Command;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class SendTransferCommand extends Command
{
    protected static $defaultName = 'app:send-transfers';

    private $em;

    private $params;


    protected function configure()
    {
        $this->setDescription('Create a new task according inputs');
    }


    public function __construct(string $name = null, EntityManagerInterface $em, ParameterBagInterface $params)
    {
        $this->em = $em;

        $this->params = $params;

        parent::__construct($name);

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $transferService = new TransferService($this->em, $this->params);
        echo 'Sending transfers...' . PHP_EOL;
        $transferService->sendTransfer();
        echo PHP_EOL;
    }
}