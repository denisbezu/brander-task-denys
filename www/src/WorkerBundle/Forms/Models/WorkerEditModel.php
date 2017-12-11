<?php


namespace WorkerBundle\Forms\Models;


use CoreBundle\Core\Core;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use WorkerBundle\Entity\Position;
use WorkerBundle\Entity\Worker;
use WorkerBundle\Forms\WorkerEditForm;

/**
 * Модель для изменения/добавления сотрудника
 * Class WorkerEditModel
 * @package WorkerBundle\Forms\Models
 */
class WorkerEditModel
{
    private $name;

    private $logo;
    /**
     * @var $rate double
     */
    private $rate;

    private $firstday;

    private $position;

    private $logoPath;
    //region Get-Set

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

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
     * @return double
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param double $rate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
    }

    /**
     * @return mixed
     */
    public function getFirstday()
    {
        return $this->firstday;
    }

    /**
     * @param mixed $firstday
     */
    public function setFirstday($firstday)
    {
        $this->firstday = $firstday;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }
    //endregion

    /**
     * Создание сотрудника на основе модели
     * @return Worker
     */
    public function getWorkerHandler()
    {
        $worker = new Worker();
        $worker->setName($this->name);
        $worker->setRate($this->rate);
        $worker->setFirstDay($this->firstday);
        $worker->setPosition($this->position);
        if($this->logo != null)
        {
            $image = WorkerEditForm::processImage($this->logo);
            $worker->setLogo($image);
        }
        return $worker;
    }

    /**
     * создание модели на основе заданного сотрудника
     * @param Worker $worker
     */
    public function createModelFromWorker(Worker $worker)
    {
        $this->name = $worker->getName();
        $this->rate = $worker->getRate();
        $this->position = $worker->getPosition();
        $this->firstday = $worker->getFirstDay();
        $this->logoPath = $worker->getLogo();
    }

    /**
     * изменение сотрудника на основе модели
     * @param Worker $worker
     * @return Worker
     */
    public function changeWorker(Worker $worker)
    {
        var_dump($this->rate);
        $worker->setName($this->name);
        $worker->setRate($this->rate);
        $worker->setPosition($this->position);
        $worker->setFirstDay($this->firstday);
        if($this->logo != null)
            $worker->setLogo(WorkerEditForm::processImage($this->logo));
        return $worker;
    }
}