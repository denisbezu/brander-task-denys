<?php
/**
 * Created by PhpStorm.
 * User: Denys
 * Date: 09.12.2017
 * Time: 19:26
 */

namespace WorkerBundle\Forms;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WorkerBundle\Entity\Position;
use WorkerBundle\Forms\Models\WorkerEditModel;

class WorkerEditForm extends AbstractType
{
    // конструктор формы для изменения/добавления сотрудника
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => 'Имя',
            'attr' => array('class' => 'form-control'),
        ]);
        $builder->add('rate', NumberType::class, [
            'label' => 'Рейт',
            'attr' => array('class' => 'form-control'),
            'scale' => 3,
        ]);
        $builder->add('position', EntityType::class, array(
            'label' => 'Должность',
            'class' => Position::class,
            'choice_label' => 'positionName',
        ));
        $builder->add('logo', FileType::class, array(
            'data_class' => null,
            'label' => 'Фото',
            'required' => 'none'
        ));
        $builder->add('firstday', DateType::class, array(
            'label' => 'Первый рабочий день',
            'placeholder' => array(
                'year' => 'Year', 'month' => 'Month', 'day' => 'Day',)
        ));

        $builder->get('logo')->setRequired(false);
        $builder->add('submit', SubmitType::class, [
            'attr' => array('class' => 'btn btn-success'),

        ]);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WorkerEditModel::class
        ]);
    }
    // обработка картинки
    public static function processImage(UploadedFile $uploadedFile)
    {
        // добавляем в указанную папку загруженную картинку
        if ($uploadedFile) {
            $path = '../../www/web/images/upload/workers/logos';

            $uploadedFileInfo = pathinfo($uploadedFile->getClientOriginalName());
            $fileName = $uploadedFile->getClientOriginalName();

            $uploadedFile->move($path, $fileName);
            return $fileName;
        } else
            return null;
    }
}