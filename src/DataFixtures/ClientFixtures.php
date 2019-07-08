<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ClientFixtures extends Fixture
{
    public const CLIENT_SFR = 'SFR';
    public const CLIENT_ORANGE = 'Orange';
    public const CLIENT_FREE = 'Free';
    public const CLIENT_BOUYGUES = 'Bouygues';

    public function load(ObjectManager $manager)
    {
        foreach ($this->getClientData() as [$name]) {
            $client = new Client();
            $client->setName($name);
            $manager->persist($client);

            if($name == 'SFR') {
                $this->addReference(self::CLIENT_SFR, $client);
            }

            if($name == 'Orange') {
                $this->addReference(self::CLIENT_ORANGE, $client);
            }

            if($name == 'Free') {
                $this->addReference(self::CLIENT_FREE, $client);
            }

            if($name == 'Bouygues') {
                $this->addReference(self::CLIENT_BOUYGUES, $client);
            }
        }

        $manager->flush();
    }

    private function getClientData(): array
    {
        return [
            // [$name];
            ['SFR'],
            ['Orange'],
            ['Free'],
            ['Bouygues']
        ];
    }
}