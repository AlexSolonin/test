<?php

namespace App\DataFixtures;

use App\Entity\Prize;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PrizeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $dataArray = [
            0 =>
                [
                    'name' => 'Laptop',
                    'amount' => 10,
                    'description' => ''
                ],
            1 =>
                [
                    'name' => 'PC',
                    'amount' => 3,
                    'description' => ''
                ],
            2 =>
                [
                    'name' => 'Cellphone',
                    'amount' => 20,
                    'description' => ''
                ],
            3 =>
                [
                    'name' => 'Car',
                    'amount' => 10,
                    'description' => 'Alfa Romeo'
                ],
        ];

        foreach ($dataArray as $value) {
            $setting = new Prize();
            $setting->setName($value['name']);
            $setting->setAmount($value['amount']);
            $setting->setDescription($value['description']);
            $manager->persist($setting);
        }

        $manager->flush();
    }
}
