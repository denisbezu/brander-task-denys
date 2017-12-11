<?php
/**
 * Created by PhpStorm.
 * User: Denys
 * Date: 10.12.2017
 * Time: 0:44
 */

namespace WorkerBundle\Forms\Models;


use DateInterval;
use DatePeriod;
use DateTime;
use WorkerBundle\Entity\Absence;
use WorkerBundle\Entity\Worker;

/**
 * модель для формы пропусков
 * Class AbsenceModel
 * @package WorkerBundle\Forms\Models
 */
class AbsenceModel
{
    private $startDate;

    private $endDate;
    /**(
     * @var Worker
     */
    private $worker;

    private $weekdays = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday');

    /**
     * AbsenceModel constructor.
     * @param $worker
     */
    public function __construct($worker = null)
    {
        $this->worker = $worker;
    }

    public function getAbsenceHandler()
    {
        if ($this->startDate != null && $this->endDate != null && $this->startDate <= $this->endDate) { // проверяем заданы ли две даты и что первая меньше второй
            $period = new DatePeriod($this->startDate, new DateInterval('P1D'), $this->endDate);
            $arrayOfDates = array_map(
                function ($item) {
                    return $item;
                },
                iterator_to_array($period)
            ); // создаем массив дат заданного периода
            $absences = array();
            foreach ($arrayOfDates as $date) {
                if ($this->checkIsntSet($date) && $this->isWeekDay($date)) // создаем пропуск, если даты еще нету в БД и если это не выходной
                {
                    $absence = new Absence();
                    $absence->setAbsenceDate($date);
                    $absence->setWorker($this->worker);
                    $this->worker->addAbsence($absence);
                    array_push($absences, $absence);
                }
            }
            return $absences;
        }
        return [];
    }

    /**
     * Проверка, добавлена ли уже дата, как пропущенная
     * @param $currentDate
     * @return bool
     */
    private function checkIsntSet($currentDate)
    {
        $flag = true;
        $workersAbsences = $this->worker->getAbsences();
        foreach ($workersAbsences as $workersAbsence) {
            /** @var Absence $workersAbsence */
            if ($workersAbsence->getAbsenceDate() == $currentDate) {
                $flag = false;
                break;
            }
        }
        return $flag;
    }

    /**
     * Проверка, является ли добавляемая дата днем недели
     * @param DateTime $date
     * @return bool
     */
    private function isWeekDay(DateTime $date)
    {
        $dayOfWeek = $date->format('l');
        if(in_array($dayOfWeek, $this->weekdays))
            return true;
        else
            return false;
    }
    //region Get-Set

    /**
     * @return mixed
     */
    public function getWorker()
    {
        return $this->worker;
    }

    /**
     * @param mixed $worker
     */
    public function setWorker($worker)
    {
        $this->worker = $worker;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param mixed $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param mixed $endDate
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }
    //endregion

}