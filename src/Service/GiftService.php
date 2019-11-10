<?php


namespace App\Service;


use App\Entity\Prize;
use App\Entity\Setting;
use App\Entity\Transfer;
use App\Entity\Winning;
use Doctrine\ORM\EntityManagerInterface;

class GiftService
{
    private $em;

    public $giftList;


    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getGiftList($userId)
    {
        $giftList = $this->em->getRepository(Winning::class)
            ->findBy(
                [
                    'id_user' => $userId
                ]
            );

        if (isset($giftList)) {
            $bonus = 0;
            $money = 0;
            $prizeList = [];

            foreach ($giftList as $item) {
                $bonus = $bonus + $item->getBonus();
                $money = $money + $item->getMoney();

                if ($item->getIdPrize() != 0) {
                    $prize = $this->em->getRepository(Prize::class)
                        ->findOneBy(
                            [
                                'id' => $item->getIdPrize()
                            ]
                        );

                    if (isset($prize)) {
                        $prizeList[] = [
                            'id' => $prize->getId(),
                            'name' => $prize->getName(),
                            'description' => $prize->getDescription()
                        ];
                    }
                }
            }

            $this->giftList = [
                'prize' => $prizeList,
                'bonus' => $bonus,
                'money' => $money
                ];
            return $this->giftList;
        }

    return false;
    }

    public function selectGift($userId)
    {
        $i=0;
            do {
                $giftType = mt_rand(0, 2);

                switch ($giftType) {
                    case 0:
                        $moneyMin = $this->getSetting('money_min');
                        $moneyMax = $this->getSetting('money_max');
                        $moneyTotal = $this->getSetting('money_total');
                        $money = round($this->mt_random_float($moneyMin, $moneyMax),2);

                        if ($money <= $moneyTotal) {
                            $amount = $this->em->getRepository(Setting::class)
                                ->findOneBy(
                                    [
                                        'name' => 'money_total'
                                    ]
                                );
                            $newAmount = $amount->getValue() - $money;
                            $amount->setValue($newAmount);
                            $this->em->flush();
                            $i = 10;
                        } else {
                            $money = 0;
                        }

                        break;
                    case 1:
                        $bonusMin = $this->getSetting('bonus_min');
                        $bonusMax = $this->getSetting('bonus_max');
                        $bonus = mt_rand($bonusMin, $bonusMax);
                        $i = 10;
                        break;
                    default:
                       $prizeAllType = $this->em->getRepository(Prize::class)->findAll();

                       if (count($prizeAllType) > 0) {
                           $prizeMax = count($prizeAllType);
                           $prizeId = mt_rand(0, $prizeMax - 1);
                           $prizeEn = $prizeAllType[$prizeId];
                           $prizeAmount = $prizeEn->getAmount();

                           if ($prizeAmount != 0) {
                               $prizeId = $prizeEn->getId();
                               $prize[] = [
                                   'id' => $prizeEn->getId(),
                                   'name' => $prizeEn->getName(),
                                   'description' => $prizeEn->getDescription()
                               ];
                               $amount = $this->em->getRepository(Prize::class)
                                   ->findOneBy(
                                       [
                                           'id' => $prizeId
                                       ]
                                   );
                               $newAmount = $amount->getAmount() - 1;
                               $amount->setAmount($newAmount);
                               $this->em->flush();
                               $i = 10;
                           }

                       }
                }
            } while ($i<10);

        $winning = new Winning();
        $winning->setIdUser($userId);
        $winning->setBonus(isset($bonus) ? $bonus : 0);
        $winning->setIdPrize(isset($prizeId) ? $prizeId : 0);
        $winning->setMoney(isset($money) ? $money : 0);
        $this->em->persist($winning);
        $this->em->flush();

        $transfer = new Transfer();
        $transfer->setIdUser($userId);
        $transfer->setIsSent(0);
        $transfer->setAmount(isset($money) ? $money : 0);
        $this->em->persist($transfer);
        $this->em->flush();

        return true;
    }

    private function mt_random_float($min, $max) {
        $float_part = mt_rand(0, mt_getrandmax())/mt_getrandmax();
        $integer_part = mt_rand($min, $max - 1);

        return $integer_part + $float_part;
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