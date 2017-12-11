<?php
/**
 * Created by PhpStorm.
 * User: Denys
 * Date: 09.12.2017
 * Time: 17:46
 */

namespace WorkerBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use WorkerBundle\Entity\Absence;
use WorkerBundle\Entity\Worker;
use WorkerBundle\Forms\AbsenceForm;
use WorkerBundle\Forms\DetailsModelForm;
use WorkerBundle\Forms\Models\AbsenceModel;
use WorkerBundle\Forms\Models\DetailsModel;
use WorkerBundle\Forms\Models\WorkerEditModel;
use WorkerBundle\Forms\WorkerEditForm;

class WorkerController extends Controller
{
    /**
     * страница со списком всех сотрудников
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function usersAction(Request $request)
    {//проверяем залогинен ли
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $em = $this->getDoctrine()->getManager();
            $usersList = $em->getRepository('WorkerBundle:Worker')->findAll(); // все сотрудники
            /**
             * @var $paginator \Knp\Component\Pager\Paginator
             */
            $paginator = $this->get('knp_paginator'); // пагинация
            $result =  $paginator->paginate(
                $usersList,
                $request->query->getInt('page', 1),
                $request->query->getInt('limit', 10)
            );

            return $this->render('@Worker/list.html.twig', [
                'users' => $result
            ]);
        } else {
            return $this->render('@Worker/access_deny.html.twig');
        }

    }

    /**страница добавления сотрудника
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $workerModel = new WorkerEditModel(); // создаем модель
        $worker_form = $this->createForm(WorkerEditForm::class, $workerModel); // создаем форму и ассоциируем с моделью
        $worker_form->handleRequest($request); // обрабатываем запрос
        if ($worker_form->isSubmitted()) { // добавляем в БД
            $worker = $workerModel->getWorkerHandler();
            $em = $this->getDoctrine()->getManager();
            $em->persist($worker);
            $em->flush();
            return $this->redirectToRoute('users');
        }
        return $this->render('@Worker/worker.html.twig',
            [
                'form' => $worker_form->createView(),
                'btnL' => 'Добавить'
            ]);
    }

    public function editAction(Request $request, Worker $worker)
    {
        $workerModel = new WorkerEditModel();
        if ($worker != null)
            $workerModel->createModelFromWorker($worker);
        $worker_form = $this->createForm(WorkerEditForm::class, $workerModel);
        $worker_form->handleRequest($request);
        if ($worker_form->isSubmitted()) {
            $worker = $workerModel->changeWorker($worker);
            $em = $this->getDoctrine()->getManager();
            $em->persist($worker);
            $em->flush();
            return $this->redirectToRoute('users');
        }
        return $this->render('@Worker/worker.html.twig',
            [
                'form' => $worker_form->createView(),

            ]);
    }

    /**
     * удаление пользователя
     * @param Request $request
     * @param Worker $worker
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Worker $worker)
    {
        //если есть пользователь, то удаляем его из БД
        if ($worker != null) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($worker);
            $em->flush();
            return $this->redirectToRoute('users');
        }
    }

    /**
     * страница просмотра инфоо о пользователе
     * @param Request $request
     * @param Worker $worker
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detailsAction(Request $request, Worker $worker)
    {
        //$now = new \DateTime();
        $datemodel = new DetailsModel(); // создаем модель, форму, ассоциируем с ней работника, обрабатываем запрос
        $datemodel->setWorker($worker);
        $dateForm = $this->createForm(DetailsModelForm::class, $datemodel);
        $dateForm->handleRequest($request);
        $salary = 'Не рассчитано';
        if ($dateForm->isSubmitted()) {
            $salary = $datemodel->calculateAbsence();
        }
        return $this->render('@Worker/worker_details.html.twig', [
            'worker' => $worker,
            'form' => $dateForm->createView(),
            'salary' => $salary,
        ]);
    }

    /**
     * страница пропусков
     * @param Request $request
     * @param Worker $worker
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function absenceAction(Request $request, Worker $worker)
    {
        $absenceModel = new AbsenceModel($worker); // модель + форма + ассоциация + пагинация + добавление в БД, если
        $wAbsences = $worker->getAbsences();
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result =  $paginator->paginate(
            $wAbsences,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        $absenceForm = $this->createForm(AbsenceForm::class, $absenceModel); // расчет пропусков - от включая до - не включая
        $absenceForm->handleRequest($request);
        if ($absenceForm->isSubmitted()) { // добавление пропусков
            $absences = $absenceModel->getAbsenceHandler(); // получаем список пропусков
            $em = $this->getDoctrine()->getManager();
            foreach ($absences as $absence) {
                $em->persist($absence);
            }
            $em->flush();
        }
        return $this->render('@Worker/absence.html.twig', [
            'worker' => $worker,
            'form' => $absenceForm->createView(),
            'absences' => $result,
        ]);
    }

}