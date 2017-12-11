<?php
/**
 * Created by PhpStorm.
 * User: Denys
 * Date: 10.12.2017
 * Time: 0:45
 */

namespace WorkerBundle\Forms;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WorkerBundle\Forms\Models\AbsenceModel;

class AbsenceForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('startDate', DateType::class, array(
            'label' => 'С',
            'placeholder' => array(
                'year' => 'Year', 'month' => 'Month', 'day' => 'Day',)
        ));

        $builder->add('endDate', DateType::class, array(
            'label' => 'По',
            'placeholder' => array(
                'year' => 'Year', 'month' => 'Month', 'day' => 'Day',)
        ));

        $builder->add('submit', SubmitType::class, [
            'label' => 'Добавить пропуски',
            'attr' => array('class' => 'btn btn-success')
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AbsenceModel::class
        ]);
    }
}