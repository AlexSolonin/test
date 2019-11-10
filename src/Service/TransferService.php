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


    public function __construct(EntityManagerInterface $em,ParameterBagInterface $params)
    {
        $this->em = $em;

        $this->params = $params;
    }

    public function sendTransfer()
    {

        $transaction_quantity = $this->getSetting('transaction_quantity');
        if ($transaction_quantity == false) {
            return false;
        }

        $transferList = $this->em->getRepository(Transfer::class)->findByIsSent((int)$transaction_quantity);

        if (isset($transferList)) {
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

    private function getSetting($name) {
        $settings = $this->em->getRepository(Setting::class)->findAll();
        foreach ($settings as $setting ) {
            if ($setting->getName() == $name) {
                $value = $setting->getValue();
            }
        }

        return isset($value) ? $value : false;
    }

}