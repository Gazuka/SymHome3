<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OutilsController extends AbstractController
{
    /** FONCTIONS DE SIMPLIFICATION DU CODE ***********************************************************************************************************************************************/
    
    /**
     * Création d'un formulaire pour un nouveau element (objet entity)
     * @return Response
     */
    protected function formElement($variables):Response{
        //Varaibles attendues obligatoires !        
        $element = $variables['element'];
        $request = $variables['request'];
        $manager = $variables['manager'];
        $classType = $variables['classType'];
        $pagedebase =  $variables['pagedebase'];
        $pagederesultat = $variables['pagederesultat'];
        $titre = $variables['titre'];
        $texteConfirmation = $variables['texteConfirmation'];
        
        //Variables attendues optionnels !
        if(array_key_exists('pagederesultatConfig', $variables)){$pagederesultatConfig = $variables['pagederesultatConfig'];}else{$pagederesultatConfig = array();}
        if(array_key_exists('dependances', $variables)){$dependances = $variables['dependances'];}else{$dependances = array();}
        if(array_key_exists('texteConfirmationEval', $variables)){$texteConfirmationEval = $variables['texteConfirmationEval'];}else{$texteConfirmationEval = array();}
        if(array_key_exists('deletes', $variables)){$deletes = $variables['deletes'];}else{$deletes = array();}

        //On crée le formulaire pour l'élèment de la classe
        $form = $this->createForm($classType, $element);        
        $form->handleRequest($request);

        //On vérifie que le formulaire soit Soumis et valide
        if($form->isSubmitted() && $form->isValid()) 
        {
            //On persist l'élément
            $manager->persist($element);

            //On persist ses dependances
            dump($dependances);
            foreach($dependances as $dependance => $elem)
            {
                eval('$objets = $element->get'.$dependance.'();');
                foreach($objets as $objet)
                {
                    if(method_exists($objet, 'add'.$elem))
                    {
                        eval('$objet->add'.$elem.'($element);'); //add pour ManyToMany --> set pour le reste... voir comment le gérer...
                    }
                    else
                    {
                        eval('$objet->set'.$elem.'($element);'); //add pour ManyToMany --> set pour le reste... voir comment le gérer...
                    }

                    $manager->persist($objet);
                }
            }  

            //Delete des elements orphelins...
            foreach($deletes as $delete)
            {
                $findBy = $delete['findBy'];

                $classEnfant = $delete['classEnfant'];

                $recup = $delete['repo']->findBy([$findBy => $element]);

                foreach($recup as $elem)
                {
                eval('$elems = $element->get'.$classEnfant.'();');
                    if(!$elems->contains($elem))
                    {
                        $manager->remove($elem);
                    }
                }
            }

            //On enregistre le tout
            $manager->flush();

            //On affiche un message de validation
            foreach($texteConfirmationEval as $key => $valeur)
            {
                eval('$valeur = '.$valeur);
                $texteConfirmation = str_replace($key, $valeur, $texteConfirmation);
            }
            $this->addFlash(
                'success',
                $texteConfirmation
            );
            

            //On affiche la page de résultat
            return $this->redirectToRoute($pagederesultat, $pagederesultatConfig);
            
        }
        // On affichage la page avec le formulaire
        return $this->render($pagedebase, [
            'form' => $form->createView(),
            'titre' => $titre,
            'element' => $element
        ]);
    }

    /**
     * Affichage de l'ensemble des éléments
     *
     * @return Response
     */
    protected function recupererElements($repo, $elements, $titre, $pagederesultat):Response {
        $recup = $repo->findAll();
        return $this->render($pagederesultat, [
            'titre' => $titre,
            $elements => $recup
        ]);
    }
}