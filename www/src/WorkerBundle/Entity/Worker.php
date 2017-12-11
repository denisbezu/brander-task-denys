<?php
/**
 * Created by PhpStorm.
 * User: Denys
 * Date: 09.12.2017
 * Time: 17:47
 */

namespace WorkerBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Worker
 * @package WorkerBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="workers")
 */
class Worker
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
    private $name;

    /**
     * @ORM\Column(name="logo", type="string", length=255, nullable=true)
     * @Assert\Image(
     *      mimeTypes= {"image/jpeg", "image/gif", "image/png", "image/jpg"},
     *      mimeTypesMessage="Неверный фомат картинки")
     */
    private $logo;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $rate;

    /**
     * @ORM\ManyToOne(targetEntity="WorkerBundle\Entity\Position", inversedBy="workers")
     * @ORM\JoinColumn(nullable=true)
     */
    private $position;

    /**
     * @ORM\Column(type="datetime")
     */
    private $firstDay;

    /**
     * @ORM\OneToMany(targetEntity="WorkerBundle\Entity\Absence", mappedBy="worker")
     */
    private $absences;
    //endregion


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->absences = new ArrayCollection();
    }



    //region Get-Set-Add
    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param mixed $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }

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
     * Set name
     *
     * @param string $name
     *
     * @return Worker
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set rate
     *
     * @param string $rate
     *
     * @return Worker
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return double
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set firstDay
     *
     * @param \DateTime $firstDay
     *
     * @return Worker
     */
    public function setFirstDay($firstDay)
    {
        $this->firstDay = $firstDay;

        return $this;
    }

    /**
     * Get firstDay
     *
     * @return \DateTime
     */
    public function getFirstDay()
    {
        return $this->firstDay;
    }

    /**
     * Set position
     *
     * @param \WorkerBundle\Entity\Position $position
     *
     * @return Worker
     */
    public function setPosition(\WorkerBundle\Entity\Position $position = null)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return \WorkerBundle\Entity\Position
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Add absence
     *
     * @param \WorkerBundle\Entity\Absence $absence
     *
     * @return Worker
     */
    public function addAbsence(\WorkerBundle\Entity\Absence $absence)
    {
        $this->absences[] = $absence;

        return $this;
    }

    /**
     * Remove absence
     *
     * @param \WorkerBundle\Entity\Absence $absence
     */
    public function removeAbsence(\WorkerBundle\Entity\Absence $absence)
    {
        $this->absences->removeElement($absence);
    }

    /**
     * Get absences
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAbsences()
    {
        return $this->absences;
    }
    //endregion
}
