<?php
/**
 * Created by PhpStorm.
 * User: Denys
 * Date: 10.12.2017
 * Time: 17:58
 */

namespace WorkerBundle\Forms\Models;


class Month
{
    //список месяцев
    public static function CreateMonths()
    {
        return array(
            'Январь' => 1,
            'Февраль' => 2,
            'Март' => 3,
            'Апрель' => 4,
            'Май' => 5,
            'Июнь' => 6,
            'Июль' => 7,
            'Август' => 8,
            'Сентябрь' => 9,
            'Октябрь' => 10,
            'Ноябрь' => 11,
            'Декабрь' => 12,
        );
    }
}