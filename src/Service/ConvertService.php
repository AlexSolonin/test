<?php


namespace App\Service;


use App\Entity\Winning;
use Doctrine\ORM\EntityManagerInterface;

class ConvertService
{
    private $em;

    private $helper;


    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

        $this->helper = new HelperService($em);
    }

    public function convert(int $userId, float $money)
    {
        $moneySum = $this->em->getRepository(Winning::class)->getSumMoneyByUserId($userId);

        $kConvert = $this->helper->getSetting('k_convert');

        if (isset($kConvert) && count($moneySum[0]) >0) {

            $newBonus =  round($money / $kConvert, 0);

            $winning = new Winning();
            $winning->setIdUser($userId);
            $winning->setBonus((int)$newBonus);
            $winning->setIdPrize(0);
            $winning->setMoney( - $money);
            $this->em->persist($winning);
            $this->em->flush();
        }

        return true;
    }

}