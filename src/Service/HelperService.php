<?php


namespace App\Service;


use App\Entity\Setting;
use Doctrine\ORM\EntityManagerInterface;

class HelperService
{
    private $em;


    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getSetting(string $name) {
        $settings = $this->em->getRepository(Setting::class)->findAll();

        foreach ($settings as $setting ) {

            if ($setting->getName() == $name) {
                $value = $setting->getValue();
            }
        }

        return isset($value) ? $value : false;
    }

}