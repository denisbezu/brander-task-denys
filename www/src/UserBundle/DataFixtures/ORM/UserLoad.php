<?php
/**
 * Created by PhpStorm.
 * User: Denys
 * Date: 07.12.2017
 * Time: 19:42
 */

namespace UserBundle\DataFixtures\ORM;

use CoreBundle\Core\Core;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use UserBundle\Entity\Role;
use UserBundle\Entity\User;
use UserBundle\Entity\UserAccount;

class UserLoad extends Fixture {

    public function load(ObjectManager $manager) {

        $roleRepo = $manager->getRepository(Role::class);
        $role = $roleRepo->findOneByRole('USER_ROLE');
        if(!$role)
            return;

        $encoder = Core::service('security.password_encoder');
        $user = new User();
        $password = $encoder->encodePassword($user, '123456');
        $user->setPassword($password);
        $user->addRole($role);
        $user->setEmail('info@utilvideo.com');

        $userAccount = new UserAccount();
        $userAccount->setFirstName('John')->setLastName('Doe');
        $userAccount->setBirthdate( new \DateTime() );
        $userAccount->setRegion('m');

        $manager = Core::em();
        $manager->beginTransaction();
        try{
            $manager->persist($user);
            $manager->flush();
            $userAccount->setUser($user);
            $manager->persist($userAccount);
            $manager->flush();
            $manager->commit();
        } catch( \Exception $e ){
            $manager->rollback();
        }
    }
    public function getOrder(){
        return 2;
    }
}