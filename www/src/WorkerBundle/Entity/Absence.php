<?php
/**
 * Created by PhpStorm.
 * User: Denys
 * Date: 09.12.2017
 * Time: 18:03
 */

namespace WorkerBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * Class Absence
 * @package WorkerBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="absences")
 */

class Absence
{
    //region Properties
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="WorkerBundle\Entity\Worker", inversedBy="absences")
     * @ORM\JoinColumn(nullable=true)
     */
    private $worker;

    /**
     * @ORM\Column(type="datetime")
     */
    private $absenceDate;
    //endregion


    //region Get-Set

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
     * Set absenceDate
     *
     * @param \DateTime $absenceDate
     *
     * @return Absence
     */
    public function setAbsenceDate($absenceDate)
    {
        $this->absenceDate = $absenceDate;

        return $this;
    }

    /**
     * Get absenceDate
     *
     * @return \DateTime
     */
    public function getAbsenceDate()
    {
        return $this->absenceDate;
    }

    /**
     * Set worker
     *
     * @param \WorkerBundle\Entity\Worker $worker
     *
     * @return Absence
     */
    public function setWorker(\WorkerBundle\Entity\Worker $worker = null)
    {
        $this->worker = $worker;

        return $this;
    }

    /**
     * Get worker
     *
     * @return \WorkerBundle\Entity\Worker
     */
    public function getWorker()
    {
        return $this->worker;
    }
    //endregion
}
