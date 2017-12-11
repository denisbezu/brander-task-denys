<?php
/**
 * Created by PhpStorm.
 * User: Denys
 * Date: 08.12.2017
 * Time: 22:26
 */

namespace UserBundle\Entity\Repository;


use Doctrine\ORM\EntityRepository;

class UserAccountRepository extends EntityRepository
{
//    public function findAllOrderedByUserName()
//    {
//        return $this->getEntityManager()
//            ->createQuery(
//                'SELECT usa FROM user_account u ORDER BY u.last_name ASC'
//            )
//            ->getResult();
//    }
}