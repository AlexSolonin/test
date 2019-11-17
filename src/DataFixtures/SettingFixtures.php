<?php

namespace App\DataFixtures;

use App\Entity\Setting;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class SettingFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $dataArray = [
            0 =>
                [
                    'name' => 'money_min',
                    'value' => 10
                ],
            1 =>
                [
                    'name' => 'money_max',
                    'value' => 456.23
                ],
            2 =>
                [
                    'name' => 'bonus_min',
                    'value' => 1
                ],
            3 =>
                [
                    'name' => 'bonus_max',
                    'value' => 150
                ],
            4 =>
                [
                    'name' => 'k_convert',
                    'value' => 1.75
                ],
            5 =>
                [
                    'name' => 'money_total',
                    'value' => 1700
                ],
            6 =>
                [
                    'name' => 'transaction_quantity',
                    'value' => 25
                ],
            7 =>
                [
                    'name' => 'bank_api_key',
                    'value' => 2598756215644
                ],
        ];

        foreach ($dataArray as $value) {
            $setting = new Setting();
            $setting->setName($value['name']);
            $setting->setValue($value['value']);
            $manager->persist($setting);
        }

        $manager->flush();
    }
}
