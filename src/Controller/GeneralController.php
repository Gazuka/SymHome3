<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GeneralController extends AbstractController
{
    /**
     * @Route("/general", name="general")
     */
    public function index()
    {
        return $this->render('general/index.html.twig', [
            'controller_name' => 'GeneralController',
        ]);
    }

    /** FONCTIONS DE SIMPLIFICATION DU CODE ***********************************************************************************************************************************************/
    /**
     * Création d'un formulaire pour un nouveau element (objet entity)
     * @return Response
     */
    private function creerElement($element, $request, $manager, $class, $pagedebase, $pagederesultat, $titre, $dependances = null):Response{
        $form = $this->createForm($class, $element);        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($element);
            //Persist des dependances
            if($dependances != null)
            {
                foreach($dependances as $dependance => $elem)
                {
                    eval('$objets = $element->get'.$dependance.'();');
                    foreach($objets as $objet)
                    {
                        eval('$objet->set'.$elem.'($element);');
                        $manager->persist($objet);
                    }
                }
            }  
            $manager->flush();
            $this->addFlash(
                'success',
                "L'élément : <strong>{$element->getNom()}</strong> a bien été créé !"
            );
            //Affichage de la liste des elements apres l'ajout du nouveau
            return $this->redirectToRoute($pagederesultat);
        }
        //Affichage de la page avec le formulaire
        return $this->render($pagedebase, [
            'form' => $form->createView(),
            'titre' => $titre
        ]);
    }

    /**
     * Affichage de l'ensemble des éléments
     *
     * @return Response
     */
    private function recupererElements($repo, $elements, $titre, $pagederesultat):Response {

        $recup = $repo->findAll();

        return $this->render($pagederesultat, [
            'titre' => $titre,
            $elements => $recup
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition d'un élément
     * @return Response
     */
    public function editElement($element, $classType, $pagederesultat, $request, $manager, $dependances = null):Response {
        $form = $this->createForm($classType, $element);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($element);
            //Persist des dependances
            if($dependances != null)
            {
                foreach($dependances as $dependance => $elem)
                {
                    eval('$objets = $element->get'.$dependance.'();');
                    foreach($objets as $objet)
                    {
                        eval('$objet->set'.$elem.'($element);');
                        $manager->persist($objet);
                    }
                }
            }  

            $manager->flush();

            $this->addFlash(
                'success',
                "L'élément' <strong>{$element->getNom()}</strong> a bien été enregistrée !"
            );
        }

        return $this->render($pagederesultat, [
            'element' => $element,
            'form' => $form->createView()
        ]);
    }

    /** GESTION DES UTILISATEURS ******************************************************************************************************************************************************/
    /**
     * Création d'un utilisateur
     * 
     * @Route("/utilisateur/new", name="general_utilisateur_new")
     *
     * @return Response
     */
    public function creerUtilisateur(Request $request, ObjectManager $manager):Response {
        $element = new Utilisateur();
        $class = UtilisateurType::class;
        $pagedebase = 'cuisine/element_new.html.twig';
        $pagederesultat = 'cuisine';
        $titre = "Création d'un utilisateur";
        return $this->creerElement($element, $request, $manager, $class, $pagedebase, $pagederesultat, $titre);
    } 
}
