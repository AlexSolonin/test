<?php


namespace App\Service;


use App\Entity\Setting;
use App\Entity\Transfer;
use App\Entity\User;
use App\Entity\Winning;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

class TransferService
{
    private $em;

    private $params;

    private $helper;


    public function __construct(
        EntityManagerInterface $em,
        ParameterBagInterface $params)
    {
        $this->em = $em;

        $this->params = $params;

        $this->helper = new HelperService($em);

    }

    public function sendTransfer()
    {

        $transaction_quantity = $this->helper->getSetting('transaction_quantity');
        if ($transaction_quantity == false) {
            return false;
        }

        $transferList = $this->em->getRepository(Transfer::class)->findByIsSent((int)$transaction_quantity);

        if (count($transferList) > 0) {
            foreach ($transferList as $transfer) {
                $bankAccount = $this->em->getRepository(User::class)->findOneBy(
                    [
                        'id' => $transfer->getidUser()
                    ]
                );

                $transferArray[] = [
                    'bank_account' => $bankAccount->getBankAccount(),
                    'amount' => $transfer->getAmount()
                ];
            }

            if (isset($transferArray)) {
                $bankService = new BankService($this->params);
                $bankService->sendTransfer($transferArray);
            }
        }
        return true;
    }

}