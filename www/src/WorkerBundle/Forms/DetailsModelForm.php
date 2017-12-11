<?php
/**
 * Created by PhpStorm.
 * User: Denys
 * Date: 10.12.2017
 * Time: 14:07
 */

namespace WorkerBundle\Forms;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WorkerBundle\Forms\Models\DetailsModel;
use WorkerBundle\Forms\Models\Month;

class DetailsModelForm extends AbstractType
{
// конструктор формы для расчета зарплаты
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('month', ChoiceType::class, array(
            'label' => 'Дата',
            'choices' => Month::CreateMonths(),
            'label_attr' => array('class' => 'h3'),
            'placeholder' => false,
        ));
        $builder->add('year', ChoiceType::class, array(
            'label' => 'Дата',
            'choices' => self::getYears(),
            'label_attr' => array('class' => 'h3'),
            'placeholder' => false,
        ));
        $builder->add('submit', SubmitType::class, [
            'label' => 'Рассчитать заплату за месяц',
            'attr' => array('class' => 'btn btn-success')
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DetailsModel::class
        ]);
    }
    // список годов
    private static function getYears()
    {
        $years = array();
        for($i = 2000; $i < 2030; $i++)
        {
           $years["$i"] = $i;
        }
        return $years;
    }
}