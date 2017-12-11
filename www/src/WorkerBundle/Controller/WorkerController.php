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
    public function usersAction(Request $request)
    {
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $em = $this->getDoctrine()->getManager();
            $usersList = $em->getRepository('WorkerBundle:Worker')->findAll();
            /**
             * @var $paginator \Knp\Component\Pager\Paginator
             */
            $paginator = $this->get('knp_paginator');
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

    public function createAction(Request $request)
    {
        $workerModel = new WorkerEditModel();
        $worker_form = $this->createForm(WorkerEditForm::class, $workerModel);
        $worker_form->handleRequest($request);
        if ($worker_form->isSubmitted()) {
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

    public function deleteAction(Request $request, Worker $worker)
    {
        //$worker = $this->getDoctrine()->getRepository(Worker::class)->find($id);
        if ($worker != null) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($worker);
            $em->flush();
            return $this->redirectToRoute('users');
        }
    }

    public function detailsAction(Request $request, Worker $worker)
    {
        $now = new \DateTime();
        $datemodel = new DetailsModel();
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

    public function absenceAction(Request $request, Worker $worker)
    {
        $absenceModel = new AbsenceModel($worker);
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

        $absenceForm = $this->createForm(AbsenceForm::class, $absenceModel);
        $absenceForm->handleRequest($request);
        if ($absenceForm->isSubmitted()) {
            $absences = $absenceModel->getAbsenceHandler();
            $em = $this->getDoctrine()->getManager();
            foreach ($absences as $absence) {
                $em->persist($absence); //добавить проверку, что уже есть
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