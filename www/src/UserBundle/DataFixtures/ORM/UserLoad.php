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
// создание пользователя
    public function load(ObjectManager $manager) {

        $roleRepo = $manager->getRepository(Role::class);
        $role = $roleRepo->findOneByRole('USER_ROLE');
        if(!$role)
            return;
    //задаем данные, создаем user-а и соответствующий useraccount
        $encoder = Core::service('security.password_encoder');
        $user = new User();
        $password = $encoder->encodePassword($user, '123456');
        $user->setPassword($password);
        $user->addRole($role);
        $user->setEmail('denys.bezu@gmail.com');

        $userAccount = new UserAccount();
        $userAccount->setFirstName('Denys')->setLastName('Bezuhlyi');
        $userAccount->setBirthdate( new \DateTime() );
        $userAccount->setRegion('m');
        //добавляем данные в БД
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