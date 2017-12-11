<?php
/**
 * Created by PhpStorm.
 * User: Denys
 * Date: 07.12.2017
 * Time: 19:11
 */

namespace UserBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Forms\Models\RegisterUserModel;
use UserBundle\Forms\RegisterUserForm;

class SecurityController extends Controller
{

    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@User/security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    public function registerAction(Request $request)
    {
        $registerModel = new RegisterUserModel();
        $register_form = $this->createForm(RegisterUserForm::class, $registerModel);
        $register_form->handleRequest($request);
        if($register_form->isSubmitted())
        {
            $user = $registerModel->getUserHandler();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('login');
        }
        return $this->render('UserBundle:security:register.html.twig',
            [
                'register_form' => $register_form->createView(),
            ]);

    }
}