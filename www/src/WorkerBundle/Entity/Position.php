<?php
/**
 * Created by PhpStorm.
 * User: Denys
 * Date: 09.12.2017
 * Time: 17:49
 */

namespace WorkerBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * Class Position
 * @package WorkerBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="positions")
 */
class Position
{
    //region Properties
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $positionName;

    /**
     * @ORM\OneToMany(targetEntity="WorkerBundle\Entity\Worker", mappedBy="position")
     */
    private $workers;
    //endregion

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->workers = new ArrayCollection();
    }

    //region Get-Set-Add
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set positionName
     *
     * @param string $positionName
     *
     * @return Position
     */
    public function setPositionName($positionName)
    {
        $this->positionName = $positionName;

        return $this;
    }

    /**
     * Get positionName
     *
     * @return string
     */
    public function getPositionName()
    {
        return $this->positionName;
    }

    /**
     * Add worker
     *
     * @param \WorkerBundle\Entity\Worker $worker
     *
     * @return Position
     */
    public function addWorker(\WorkerBundle\Entity\Worker $worker)
    {
        $this->workers[] = $worker;

        return $this;
    }

    /**
     * Remove worker
     *
     * @param \WorkerBundle\Entity\Worker $worker
     */
    public function removeWorker(\WorkerBundle\Entity\Worker $worker)
    {
        $this->workers->removeElement($worker);
    }

    /**
     * Get workers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWorkers()
    {
        return $this->workers;
    }
    //endregion
}
