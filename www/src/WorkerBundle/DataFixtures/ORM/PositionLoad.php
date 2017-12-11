<?php
/**
 * Created by PhpStorm.
 * User: Denys
 * Date: 09.12.2017
 * Time: 18:17
 */

namespace WorkerBundle\DataFixtures\ORM;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use WorkerBundle\Entity\Position;

class PositionLoad extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $positionRepo = $manager->getRepository(Position::class);
        if (!$positionRepo->findByPositionName("Директор")) {
            $director = new Position();
            $director->setPositionName("Директор");
            $manager->persist($director);
        }
        if (!$positionRepo->findByPositionName("Тестировщик")) {
            $tester = new Position();
            $tester->setPositionName("Тестировщик");
            $manager->persist($tester);
        }
        if (!$positionRepo->findByPositionName("Аналитик")) {
            $analyst = new Position();
            $analyst->setPositionName("Аналитик");
            $manager->persist($analyst);
        }
        $manager->flush();

    }
    public function getOrder()
    {
        return -999999;
    }
}