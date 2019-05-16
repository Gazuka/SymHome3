<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/login", name="account_login")
     */
    public function login()
    {
        return $this->render('account/login.html.twig');
    }

    /**
     * Permet d'afficher le formulaure d'inscription
     * 
     * @Route("/register", name="account_register")
     *
     * @return Response
     */
   public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(RegistrationType::class, $utilisateur);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($utilisateur, $utilisateur->getHash());
            $utilisateur->setHash($hash);
            $manager->persist($utilisateur);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre compte a bien été créé ! Vous pouvez maintenant vous connecter !"
            );
            return $this->redirectToRoute('account_login');
        }
        return $this->render('account/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
