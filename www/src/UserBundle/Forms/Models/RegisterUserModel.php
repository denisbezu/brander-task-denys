<?php
/**
 * Created by PhpStorm.
 * User: Denys
 * Date: 07.12.2017
 * Time: 20:39
 */

namespace UserBundle\Forms\Models;
use CoreBundle\Core\Core;
use Symfony\Component\Validator\Constraints as Assert;
use UserBundle\Entity\User;
use UserBundle\Entity\UserAccount;

/**задаем класс-модель для формы
 * Class RegisterUserModel
 * @package UserBundle\Forms\Models
 */
class RegisterUserModel
{
    public $name;

    public $email;

    public $password;

    public $birthday;

    public $region;

    //region Get-Set


    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param mixed $birthday
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }

    /**
     * @return mixed
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param mixed $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }
    //endregion

    /**создание user и useraccount на основе модели
     * @return User
     */
    public function getUserHandler()
    {
        $user = new User();
        $account = new UserAccount();
        $account->setName($this->name);
        $account->setBirthdate($this->birthday);
        $account->setRegion($this->region);
        $user->setEmail($this->email);
        $user->setAccount($account);
        $encoder = Core::service('security.password_encoder');
        $password = $encoder->encodePassword($user, $this->password);
        $user->setPassword($password);
        return $user;
    }
}