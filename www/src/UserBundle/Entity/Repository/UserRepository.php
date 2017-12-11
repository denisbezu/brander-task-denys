<?php
/**
 * Created by PhpStorm.
 * User: Denys
 * Date: 07.12.2017
 * Time: 19:51
 */

namespace UserBundle\Entity\Repository;


use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRepository extends EntityRepository implements UserLoaderInterface
{

    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)->setParameter('email', $username)
            ->getQuery()->getOneOrNullResult();
    }

    public function findAllOrderedByUserName()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT us FROM UserBundle:User us ORDER BY us.username ASC'
            )
            ->getResult();
    }
}