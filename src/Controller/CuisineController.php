<?php

namespace App\Controller;

use DateTime;
use App\Entity\Boite;
use App\Entity\Unite;
use App\Entity\Aliment;
use App\Entity\Recette;
use App\Form\BoiteType;
use App\Form\UniteType;
use App\Entity\Stockage;
use App\Form\AlimentType;
use App\Form\RecetteType;
use App\Form\StockageType;
use App\Entity\Preparation;
use App\Entity\TypeAliment;
use App\Entity\EtapeRecette;
use App\Form\PreparationType;
use App\Form\TypeAlimentType;
use App\Repository\BoiteRepository;
use App\Repository\UniteRepository;
use App\Entity\PreparationDateManger;
use App\Repository\AlimentRepository;
use App\Repository\RecetteRepository;
use App\Repository\StockageRepository;
use App\Repository\PreparationRepository;
use App\Repository\TypeAlimentRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CuisineController extends AbstractController
{
    /**
     * @Route("/cuisine", name="cuisine")
     * @Route("/", name="accueil")
     */
    public function index()
    {
        return $this->render('cuisine/index.html.twig', [
            'controller_name' => 'CuisineController',
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
    
    /** GESTION DES ALIMENTS **************************************************************************************************************************************************************/
    /**
     * Création d'un aliment
     * 
     * @Route("/cuisine/aliment/new", name="cuisine_aliment_new")
     * @IsGranted("ROLE_ADMIN")
     * 
     * @return Response
     */
    public function creerAliment(Request $request, ObjectManager $manager):Response {
        $element = new Aliment();
        $class = AlimentType::class;
        $pagedebase = 'cuisine/element_new.html.twig';
        $pagederesultat = 'cuisine_aliments_liste';
        $titre = "Création d'un aliment";
        return $this->creerElement($element, $request, $manager, $class, $pagedebase, $pagederesultat, $titre);
    }    

    /**
     * Affiche l'ensemble des aliments
     * 
     * @Route("/cuisine/aliments", name="cuisine_aliments_liste")
     *
     * @return Response
     */
    public function recupererAliments(AlimentRepository $repo):Response {
        $elements = "aliments";
        $titre = "Listing des aliments";
        $pagederesultat = "cuisine/aliments_liste.html.twig";
        return $this->recupererElements($repo, $elements, $titre, $pagederesultat);
    }

    /**
     * Permet d'afficher le formulaire d'édition d'un aliment
     *
     * @Route("/cuisine/aliment/{id}/edit", name="cuisine_aliment_edit")
     * @return Response
     */
    public function editAliment(Aliment $aliment, Request $request, ObjectManager $manager):Response {
        $element = $aliment;
        $classType = AlimentType::class;
        $pagederesultat = "cuisine/element_edit.html.twig";
        return $this->editElement($element, $classType, $pagederesultat, $request, $manager);
    }

    /** GESTION DES BOITES ****************************************************************************************************************************************************************/
    /**
     * Création d'une boite vide
     * 
     * @Route("/cuisine/boite/new", name="cuisine_boite_new")
     *
     * @return Response
     */
    public function creerBoite(Request $request, ObjectManager $manager):Response {
        $element = new Boite();
        $class = BoiteType::class;
        $pagedebase = 'cuisine/element_new.html.twig';
        $pagederesultat = 'cuisine_boites_liste';
        $titre = "Création d'une boite";
        return $this->creerElement($element, $request, $manager, $class, $pagedebase, $pagederesultat, $titre);
    }    

    /**
     * Affiche l'ensemble des boites
     * 
     * @Route("/cuisine/boites", name="cuisine_boites_liste")
     *
     * @return Response
     */
    public function recupererBoites(BoiteRepository $repo):Response {
        $elements = "boites";
        $titre = "Listing des boites";
        $pagederesultat = "cuisine/boites_liste.html.twig";
        return $this->recupererElements($repo, $elements, $titre, $pagederesultat);
    }

    /**
     * Permet d'afficher le formulaire d'édition d'une boite
     *
     * @Route("/cuisine/boite/{id}/edit", name="cuisine_boite_edit")
     * @return Response
     */
    public function editBoite(Boite $boite, Request $request, ObjectManager $manager):Response {
        $element = $boite;
        $classType = BoiteType::class;
        $pagederesultat = "cuisine/element_edit.html.twig";
        return $this->editElement($element, $classType, $pagederesultat, $request, $manager);
    }

    /**
     * Vider une boite
     *
     * @Route("/cuisine/boite/{id}/vider", name="cuisine_boite_vider")
     * @return Response
     */
    public function viderBoite(Boite $boite, Request $request, ObjectManager $manager):Response {
        $preparation = $boite->getPreparation();
        $preparation->manger($boite, new DateTime('NOW'));
        $manager->persist($preparation);
        foreach($preparation->getDatesManger() as $dateManger)
        {
            $manager->persist($dateManger);
        }
        $boite->vider();
        $manager->persist($boite);
        $manager->flush();
        $this->addFlash(
            'danger',
            "La boite : <strong>{$boite->getNom()}</strong> a bien été vidée !"
        );
        //Affichage de la liste des boites
        return $this->redirectToRoute('cuisine_boites_liste');
    }

    /** N'est pas utilisé pour le moment
     * Retourne un tableau des boites vides (pour affichage formulaire)
     */
    private function recupererBoitesVides(BoiteRepository $repo) {
        $elements = "boites";
        $titre = "Listing des boites";
        $pagederesultat = "cuisine/boites_liste.html.twig";

        $recup = $repo->findAll();
        $recupvide = array();
        foreach($recup as $boite)
        {
            if(sizeof($boite->getPreparations()) == 0)
            {
                array_push($recupvide, $boite);
            }
        }
        return $recupvide;
    }
    
    /** GESTION DES PREPARATIONS ******************************************************************************************************************************************************/
    /**
     * Création d'une préparation
     * 
     * @Route("/cuisine/preparation/new", name="cuisine_preparation_new")
     *
     * @return Response
     */
    public function creerPreparation(Request $request, ObjectManager $manager):Response {
        $element = new Preparation();
        $class = PreparationType::class;
        $pagedebase = 'cuisine/element_new.html.twig';
        $pagederesultat = 'cuisine_preparations_liste';
        $titre = "Création d'une préparation";
        $dependances = array('Boites' => 'Preparation');
        return $this->creerElement($element, $request, $manager, $class, $pagedebase, $pagederesultat, $titre, $dependances);
    }

    /**
     * Affiche l'ensemble des préparations
     * 
     * @Route("/cuisine/preparation", name="cuisine_preparations_liste")
     *
     * @return Response
     */
    public function recupererPreparations(PreparationRepository $repo):Response {
        $elements = "preparations";
        $titre = "Listing des préparations";
        $pagederesultat = "cuisine/preparations_liste.html.twig";
        return $this->recupererElements($repo, $elements, $titre, $pagederesultat);
    } 
    
    /**
     * Permet d'afficher le formulaire d'édition d'une préparation
     *
     * @Route("/cuisine/preparation/{id}/edit", name="cuisine_preparation_edit")
     * @return Response
     */
    public function editPreparation(Preparation $preparation, Request $request, ObjectManager $manager):Response {
        $element = $preparation;
        $classType = PreparationType::class;
        $pagederesultat = "cuisine/element_edit.html.twig";
        $dependances = array('Boites' => 'Preparation');
        return $this->editElement($element, $classType, $pagederesultat, $request, $manager, $dependances);
    }

    /** GESTION DES RECETTES **************************************************************************************************************************************************************/
    /**
     * Création d'une recette
     * 
     * @Route("/cuisine/recette/new", name="cuisine_recette_new")
     *
     * @return Response
     */
    public function creerRecette(Request $request, ObjectManager $manager):Response {
        $element = new Recette();
        $etape = new EtapeRecette();
        $element->addEtapesRecette($etape);
        $class = RecetteType::class;
        $pagedebase = 'cuisine/element_new.html.twig';
        $pagederesultat = 'cuisine_recettes_liste';
        $titre = "Création d'une recette";
        return $this->creerElement($element, $request, $manager, $class, $pagedebase, $pagederesultat, $titre);
    }

    /**
     * Affiche l'ensemble des recettes
     * 
     * @Route("/cuisine/recette", name="cuisine_recettes_liste")
     *
     * @return Response
     */
    public function recupererRecettes(RecetteRepository $repo):Response {
        $elements = "recettes";
        $titre = "Listing des recettes";
        $pagederesultat = "cuisine/recettes_liste.html.twig";
        return $this->recupererElements($repo, $elements, $titre, $pagederesultat);
    } 
    
    /**
     * Permet d'afficher le formulaire d'édition d'une recette
     *
     * @Route("/cuisine/recette/{id}/edit", name="cuisine_recette_edit")
     * @return Response
     */
    public function editRecette(Recette $recette, Request $request, ObjectManager $manager):Response {
        $element = $recette;
        $classType = RecetteType::class;
        $pagederesultat = "cuisine/element_edit.html.twig";
        return $this->editElement($element, $classType, $pagederesultat, $request, $manager);
    }

    /** GESTION DES STOCKAGES *************************************************************************************************************************************************************/
    /**
     * Création d'un stockage
     * 
     * @Route("/cuisine/stockage/new", name="cuisine_stockage_new")
     *
     * @return Response
     */
    public function creerStockage(Request $request, ObjectManager $manager):Response {
        $element = new Stockage();
        $class = StockageType::class;
        $pagedebase = 'cuisine/element_new.html.twig';
        $pagederesultat = 'cuisine_stockages_liste';
        $titre = "Création d'un stockage";
        return $this->creerElement($element, $request, $manager, $class, $pagedebase, $pagederesultat, $titre);
    }

    /**
     * Affiche l'ensemble des stockages
     * 
     * @Route("/cuisine/stockage", name="cuisine_stockages_liste")
     *
     * @return Response
     */
    public function recupererStockages(StockageRepository $repo):Response {
        $elements = "stockages";
        $titre = "Listing des stockages";
        $pagederesultat = "cuisine/stockages_liste.html.twig";
        return $this->recupererElements($repo, $elements, $titre, $pagederesultat);
    } 
    
    /**
     * Permet d'afficher le formulaire d'édition d'un stockage
     *
     * @Route("/cuisine/stockage/{id}/edit", name="cuisine_stockage_edit")
     * @return Response
     */
    public function editStockage(Stockage $stockage, Request $request, ObjectManager $manager):Response {
        $element = $stockage;
        $classType = StockageType::class;
        $pagederesultat = "cuisine/element_edit.html.twig";
        return $this->editElement($element, $classType, $pagederesultat, $request, $manager);
    }

    /** GESTION DES TYPES D'ALIMENTS ******************************************************************************************************************************************************/
    /**
     * Création d'un type d'aliment
     * 
     * @Route("/cuisine/typealiment/new", name="cuisine_typealiment_new")
     *
     * @return Response
     */
    public function creerTypeAliment(Request $request, ObjectManager $manager):Response {
        $element = new TypeAliment();
        $class = TypeAlimentType::class;
        $pagedebase = 'cuisine/element_new.html.twig';
        $pagederesultat = 'cuisine_typesaliment_liste';
        $titre = "Création d'un type d'aliment";
        return $this->creerElement($element, $request, $manager, $class, $pagedebase, $pagederesultat, $titre);
    } 
    
    /**
     * Affiche l'ensemble des types d'aliment
     * 
     * @Route("/cuisine/typesaliment", name="cuisine_typesaliment_liste")
     *
     * @return Response
     */
    public function recupererTypesAliment(TypeAlimentRepository $repo):Response {
        $elements = "typesaliment";
        $titre = "Listing des types d\'aliment";
        $pagederesultat = "cuisine/typesaliment_liste.html.twig";
        return $this->recupererElements($repo, $elements, $titre, $pagederesultat);
    }    

    /**
     * Permet d'afficher le formulaire d'édition d'un type d'aliment
     *
     * @Route("/cuisine/typealiement/{id}/edit", name="cuisine_typealiment_edit")
     * @return Response
     */
    public function editTypeAliment(TypeAliment $typeAliment, Request $request, ObjectManager $manager):Response {
        $element = $typeAliment;
        $classType = TypeAlimentType::class;
        $pagederesultat = "cuisine/element_edit.html.twig";
        return $this->editElement($element, $classType, $pagederesultat, $request, $manager);
    }

    /** GESTION DES UNITES ****************************************************************************************************************************************************************/
    /**
     * Création d'une unité
     * 
     * @Route("/cuisine/unite/new", name="cuisine_unite_new")
     *
     * @return Response
     */
    public function creerUnite(Request $request, ObjectManager $manager):Response {
        $element = new Unite();
        $class = UniteType::class;
        $pagedebase = 'cuisine/element_new.html.twig';
        $pagederesultat = 'cuisine_unites_liste';
        $titre = "Création d'une unité";
        return $this->creerElement($element, $request, $manager, $class, $pagedebase, $pagederesultat, $titre);
    }

    /**
     * Affiche l'ensemble des unités
     * 
     * @Route("/cuisine/unite", name="cuisine_unites_liste")
     *
     * @return Response
     */
    public function recupererUnites(UniteRepository $repo):Response {
        $elements = "unites";
        $titre = "Listing des unités";
        $pagederesultat = "cuisine/unites_liste.html.twig";
        return $this->recupererElements($repo, $elements, $titre, $pagederesultat);
    } 
    
    /**
     * Permet d'afficher le formulaire d'édition d'une unite
     *
     * @Route("/cuisine/unite/{id}/edit", name="cuisine_unite_edit")
     * @return Response
     */
    public function editUnite(Unite $unite, Request $request, ObjectManager $manager):Response {
        $element = $unite;
        $classType = UniteType::class;
        $pagederesultat = "cuisine/element_edit.html.twig";
        return $this->editElement($element, $classType, $pagederesultat, $request, $manager);
    }
}
