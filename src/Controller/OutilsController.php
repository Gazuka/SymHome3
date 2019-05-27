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
        if(array_key_exists('dependances', $variables)){$dependances = $variables['dependances'];}else{$dependances = null;}
        if(array_key_exists('texteConfirmationEval', $variables)){$texteConfirmationEval = $variables['texteConfirmationEval'];}else{$texteConfirmationEval = array();}

        //On crée le formulaire pour l'élèment de la classe
        $form = $this->createForm($classType, $element);        
        $form->handleRequest($request);

        //On vérifie que le formulaire soit Soumis et valide
        if($form->isSubmitted() && $form->isValid()) 
        {
            //On persist l'élément
            $manager->persist($element);

            //On persist ses dependances
            if($dependances != null)
            {
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
            }  

            //On enregistre le tout
            $manager->flush();

            //On affiche un message de validation
            foreach($texteConfirmationEval as $key => $valeur)
            {
                dump($valeur);
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

    /** A supprimer rapidement
     * Permet d'afficher le formulaire d'édition d'un élément
     * @return Response
     */
    /*protected function editElement($element, $classType, $pagederesultat, $request, $manager, $dependances = null):Response {
        //On crée le formulaire pour l'élèment de la classe
        $form = $this->createForm($classType, $element);
        $form->handleRequest($request);

        //On vérifie que le formulaire soit Soumis et valide
        if($form->isSubmitted() && $form->isValid()) 
        {
            //On persist l'élément
            $manager->persist($element);

            //On persist ses dependances
            if($dependances != null)
            {
                foreach($dependances as $dependance => $elem)
                {
                    eval('$objets = $element->get'.$dependance.'();');
                    foreach($objets as $objet)
                    {
                        eval('$objet->add'.$elem.'($element);');  //add pour ManyToMany --> set pour le reste... voir comment le gérer...
                        $manager->persist($objet);
                    }
                }
            }  

            $manager->flush();
            $this->addFlash(
                'success',
                "L'élément' a bien été enregistrée !"
            );
        }
        return $this->render($pagederesultat, [
            'element' => $element,
            'form' => $form->createView()
        ]);
    }*/

    /** A supprimer rapidement
     * Création d'un formulaire pour un nouveau element (objet entity)
     * @return Response
     */
    /*protected function creerElement($element, $request, $manager, $classType, $pagedebase, $pagederesultat, $titre, $dependances = null):Response{
        //On crée le formulaire pour l'élèment de la classe
        $form = $this->createForm($classType, $element);        
        $form->handleRequest($request);

        //On vérifie que le formulaire soit Soumis et valide
        if($form->isSubmitted() && $form->isValid()) 
        {
            //On persist l'élément
            $manager->persist($element);

            //On persist ses dependances
            if($dependances != null)
            {
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
            }  

            //On enregistre le tout
            $manager->flush();

            //On affiche un message de validation
            $this->addFlash(
                'success',
                "L'élément : a bien été créé !"
            );
            

            //On affiche la page de résultat
            if(is_array($pagederesultat))
            {
                //Affichage de la liste des elements avec l'id
                return $this->redirectToRoute($pagederesultat['page'], $pagederesultat);
            }
            else
            {
                //Affichage de la liste des elements apres l'ajout du nouveau
                return $this->redirectToRoute($pagederesultat);
            }
            
        }
        // On affichage la page avec le formulaire
        return $this->render($pagedebase, [
            'form' => $form->createView(),
            'titre' => $titre
        ]);
    }*/
}