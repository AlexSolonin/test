<?php


namespace App\Service;


use App\Entity\Setting;
use App\Entity\Winning;
use Doctrine\ORM\EntityManagerInterface;

class ConvertService
{
    private $em;


    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function convert($userId, $money)
    {
        $moneySum = $this->em->getRepository(Winning::class)->getSumMoneyByUserId($userId);
        $kConvert = $this->getSetting('k_convert');

        if (isset($kConvert) && isset($moneySum[0])) {

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