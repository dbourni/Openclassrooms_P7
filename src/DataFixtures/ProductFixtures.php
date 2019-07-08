<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        foreach ($this->getProductData() as [$name, $price, $description]) {
            $product = new Product();
            $product->setName($name);
            $product->setPrice($price);
            $product->setDescription($description);
            $manager->persist($product);
        }

        $manager->flush();
    }

    private function getProductData(): array
    {
        return [
            // [$name, $price, $description];
            ['Samsung Galaxy A50', '263', 'Le Samsung Galaxy A50 (A5 2019) a été officialisé le 25 février 2019. Il propose un écran Super AMOLED, trois caméras à l\'arrière, Samsung One...'],
            ['Samsung Galaxy A10', '133', 'Le Samsung Galaxy A10 est un smartphone d\'entrée de gamme annoncé en mars 2019. Il est équipé d\'un écran IPS LCD de 6,2 pouces avec...'],
            ['Samsung Galaxy A30', '195', 'Le Samsung Galaxy A30 a été officialisé en février 2019. Il propose un grand écran de 6,4 pouces Super AMOLED, l\'Exynos 7904 et un capteur...'],
            ['Huawei P30 Pro', '794', 'Le Huawei P30 Pro serait le prochain flagship du constructeur chinois. Équipé d\'une puce Kirin 980, il devrait disposer d\'un quadruple capteur photo compatible zoom...'],
            ['Huawei P30', '584', 'Le Huawei P30 a été annoncé le 26 mars 2019. Équipé d\'une puce Kirin 980, il devrait disposer d\'un triple capteur photo à son dos,...'],
            ['Huawei P20 Pro', '434', 'Le Huawei P20 Pro est la version grand format du nouveau flagship de Huawei annoncé le 27 Mars 2018 à Paris. Il dispose d\'un SoC...']
        ];
    }
}