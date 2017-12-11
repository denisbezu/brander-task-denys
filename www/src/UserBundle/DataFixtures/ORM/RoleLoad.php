<?php
/**
 * Created by PhpStorm.
 * User: Denys
 * Date: 07.12.2017
 * Time: 15:18
 */

namespace UserBundle\DataFixtures\ORM;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UserBundle\Entity\Role;

class RoleLoad extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $roleRepo = $manager->getRepository(Role::class);
        $role = $roleRepo->findByRole('USER_ROLE');
        if(!$role) {
            $role = new Role();
            $role->setName("USER ROLE");
            $role->setRole("USER_ROLE");
            $manager->persist($role);
            $manager->flush();
        }

    }

    public function getOrder()
    {
        return -9999;
    }
}