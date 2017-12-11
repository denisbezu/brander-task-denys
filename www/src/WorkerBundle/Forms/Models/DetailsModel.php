<?php
/**
 * Created by PhpStorm.
 * User: Denys
 * Date: 10.12.2017
 * Time: 14:07
 */

namespace WorkerBundle\Forms\Models;


use Symfony\Component\Validator\Constraints\DateTime;
use WorkerBundle\Entity\Absence;
use WorkerBundle\Entity\Worker;

/**
 * модель для формы просмотра сотрудника, а именно для расчета зарплаты
 * Class DetailsModel
 * @package WorkerBundle\Forms\Models
 */
class DetailsModel
{
    private $month;

    private $year;


    /**
     * @var Worker
     */
    private $worker;

    private $weekdays = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday');

    private $weekends = array('Saturday', 'Sunday');
    //region Get-Set
    /**
     * @var double
     */
    private $salary = 0;

    /**
     * @return Worker
     */
    public function getWorker()
    {
        return $this->worker;
    }

    /**
     * @param Worker $worker
     */
    public function setWorker($worker)
    {
        $this->worker = $worker;
    }

    /**
     * @return mixed
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param mixed $month
     */
    public function setMonth($month)
    {
        $this->month = $month;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param mixed $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    //endregion

    public function calculateAbsence()
    {
        $monthDate = $this->month; //заданный месяц
        $yearDate = $this->year; //заданный год
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $monthDate, $yearDate); //количество дней в месяце
        /** @var \DateTime $currentDate */
        $currentDate = new \DateTime(); //текущая дата
        $currentMonth = $currentDate->format('m');// месяц текущей даты
        $currentYear = $currentDate->format('Y');// год текущей даты
        $monthAbsances = $this->calculateWorkerMonthAbsences($monthDate, $yearDate);//пропуски в месяце
        $startWorkDay = $this->worker->getFirstDay(); // дата начала работы
        if ($monthDate == $currentMonth && $yearDate == $currentYear) { // если заданный месяц и год равны мегодняшним месяцу и году
            $this->calculateRate($currentDate->format('d'), $monthDate, $yearDate, $monthAbsances, $startWorkDay); // считаем с даты начала работы
        } elseif (date_create_from_format('d-m-Y', "01-$monthDate-$yearDate") > $currentDate
        || $startWorkDay > date_create_from_format('d-m-Y', "$daysInMonth-$monthDate-$yearDate")) { // если заданный параметры больше текцщей даты или меньше первого дня работы, то зарплата 0
            $this->salary = 0;
        } else {

            $this->calculateRate($daysInMonth, $monthDate, $yearDate, $monthAbsances, $startWorkDay);
        }
        return $this->salary;
    }

    /**
     * расчет зарплаты
     * @param $endDate
     * @param $monthDate
     * @param $yearDate
     * @param $monthAbsances
     * @param $firstDay
     */
    private function calculateRate($endDate, $monthDate, $yearDate, $monthAbsances, $firstDay)
    {
        $startFrom = 1; // начинаем с первого дня
                /** @var \DateTime $firstDay */
        if($firstDay > "01-$monthDate-$yearDate") // если первый день работы начат в этом месяце, то начинаем с него
            $startFrom = $firstDay->format('d');
        for ($i = $startFrom; $i <= $endDate; $i++) { // расчет зарплаты с учетом пропусков
            $currDate = date_create_from_format('d-m-Y', "$i-$monthDate-$yearDate");
            if (in_array($currDate->format('l'), $this->weekdays) && !in_array($currDate->format('d-m-Y'), $monthAbsances)) {
                $this->salary += 8 * $this->worker->getRate();
            }
        }
    }

    /**
     *
     * @param $month
     * @param $year
     * @return array массив из дат пропусков за заданный год и месяц
     */
    private function calculateWorkerMonthAbsences($month, $year)
    {
        $monthAbs = array();
        /** @var Absence $absence */
        foreach ($this->worker->getAbsences() as $absence) {

            if ($absence->getAbsenceDate()->format('m') == $month && $absence->getAbsenceDate()->format('Y') == $year) {
                array_push($monthAbs, $absence->getAbsenceDate()->format('d-m-Y'));
            }
        }
        return $monthAbs;
    }
}