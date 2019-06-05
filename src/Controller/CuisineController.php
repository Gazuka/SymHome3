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
use App\Controller\OutilsController;
use App\Entity\PreparationDateManger;
use App\Repository\AlimentRepository;
use App\Repository\RecetteRepository;
use App\Repository\StockageRepository;
use App\Repository\IngredientRepository;
use App\Repository\PreparationRepository;
use App\Repository\TypeAlimentRepository;
use App\Repository\EtapeRecetteRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CuisineController extends OutilsController
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
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = new Aliment();
        $variables['classType'] = AlimentType::class;
        $variables['pagedebase'] = 'cuisine/element_new.html.twig';
        $variables['pagederesultat'] = 'cuisine_aliments_liste';
        $variables['titre'] = "Création d'un aliment";
        $variables['texteConfirmation'] = "L'aliment ### a bien été créé !";
        $variables['texteConfirmationEval']["###"] = '$element->getNom();';
        
        return $this->formElement($variables);
    }    

    /**
     * Affiche l'ensemble des aliments
     * 
     * @Route("/cuisine/aliments", name="cuisine_aliments_liste")
     * @IsGranted("ROLE_ADMIN")
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
     * @IsGranted("ROLE_ADMIN")
     * 
     * @return Response
     */
    public function editAliment(Aliment $aliment, Request $request, ObjectManager $manager):Response {
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = $aliment;
        $variables['classType'] = AlimentType::class;
        $variables['pagedebase'] = 'cuisine/element_edit.html.twig';
        $variables['pagederesultat'] = 'cuisine_aliments_liste';
        $variables['titre'] = "Edition de l'aliment".$aliment->getNom().".";
        $variables['texteConfirmation'] = "L'aliment ### a bien été modifiée !";
        $variables['texteConfirmationEval']["###"] = '$element->getNom();';
        
        return $this->formElement($variables);
    }

    /** GESTION DES BOITES ****************************************************************************************************************************************************************/
    /**
     * Création d'une boite vide
     * 
     * @Route("/cuisine/boite/new", name="cuisine_boite_new")
     * @IsGranted("ROLE_ADMIN")
     *
     * @return Response
     */
    public function creerBoite(Request $request, ObjectManager $manager):Response {
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = new Boite();
        $variables['classType'] = BoiteType::class;
        $variables['pagedebase'] = 'cuisine/element_new.html.twig';
        $variables['pagederesultat'] = 'cuisine_boites_liste';
        $variables['titre'] = "Création d'une boite";
        $variables['texteConfirmation'] = "La boite ### a bien été créé !";
        $variables['texteConfirmationEval']["###"] = '$element->getNom();';
        
        return $this->formElement($variables);
    }    

    /**
     * Affiche l'ensemble des boites
     * 
     * @Route("/cuisine/boites", name="cuisine_boites_liste")
     * @IsGranted("ROLE_ADMIN")
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
     * @IsGranted("ROLE_ADMIN")
     * 
     * @return Response
     */
    public function editBoite(Boite $boite, Request $request, ObjectManager $manager):Response {
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = $boite;
        $variables['classType'] = BoiteType::class;
        $variables['pagedebase'] = 'cuisine/element_edit.html.twig';
        $variables['pagederesultat'] = 'cuisine_boites_liste';
        $variables['titre'] = "Edition de la boite ".$boite->getNom().".";
        $variables['texteConfirmation'] = "La boite ### a bien été modifiée !";
        $variables['texteConfirmationEval']["###"] = '$element->getNom();';
        
        return $this->formElement($variables);
    }

    /**
     * Vider une boite
     *
     * @Route("/cuisine/boite/{id}/vider", name="cuisine_boite_vider")
     * @IsGranted("ROLE_ADMIN")
     * 
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
    /*private function recupererBoitesVides(BoiteRepository $repo) {
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
    }*/
    
    /** GESTION DES PREPARATIONS ******************************************************************************************************************************************************/
    /**
     * Création d'une préparation
     * 
     * @Route("/cuisine/preparation/new", name="cuisine_preparation_new")
     * @IsGranted("ROLE_ADMIN")
     *
     * @return Response
     */
    public function creerPreparation(Request $request, ObjectManager $manager):Response {
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = new Preparation();
        $variables['classType'] = PreparationType::class;
        $variables['pagedebase'] = 'cuisine/element_new.html.twig';
        $variables['pagederesultat'] = 'cuisine_preparations_liste';
        $variables['titre'] = "Création d'une préparation";
        $variables['dependances'] = array('Boites' => 'Preparation');
        $variables['texteConfirmation'] = "La préparation ### a bien été créé !";
        $variables['texteConfirmationEval']["###"] = '$element->getNom();';
        
        return $this->formElement($variables);
    }

    /**
     * Affiche l'ensemble des préparations
     * 
     * @Route("/cuisine/preparation", name="cuisine_preparations_liste")
     * @IsGranted("ROLE_ADMIN")
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
     * @IsGranted("ROLE_ADMIN")
     * 
     * @return Response
     */
    public function editPreparation(Preparation $preparation, Request $request, ObjectManager $manager):Response {
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = $preparation;
        $variables['classType'] = PreparationType::class;
        $variables['pagedebase'] = 'cuisine/element_edit.html.twig';
        $variables['pagederesultat'] = 'cuisine_preparations_liste';
        $variables['titre'] = "Edition de l'aliment".$preparation->getNom().".";
        $variables['dependances'] = array('Boites' => 'Preparation');
        $variables['texteConfirmation'] = "La préparation ### a bien été modifiée !";
        $variables['texteConfirmationEval']["###"] = '$element->getNom();';
        
        return $this->formElement($variables);
    }

    /** GESTION DES RECETTES **************************************************************************************************************************************************************/
    /**
     * Création d'une recette
     * 
     * @Route("/cuisine/recette/new", name="cuisine_recette_new")
     * @IsGranted("ROLE_ADMIN")
     *
     * @return Response
     */
    public function creerRecette(Request $request, ObjectManager $manager):Response {
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = new Recette();
        $variables['classType'] = RecetteType::class;
        $variables['pagedebase'] = 'cuisine/recette_new.html.twig';
        $variables['pagederesultat'] = 'cuisine_recettes_liste';
        $variables['titre'] = "Création d'une recette";
        $variables['dependances'] = array('EtapesRecette' => 'Recette', 'Ingredients' => 'Recette');
        $variables['texteConfirmation'] = "La recette ### a bien été créé !";
        $variables['texteConfirmationEval']["###"] = '$element->getNom();';
        
        return $this->formElement($variables);
    }

    /**
     * Affiche l'ensemble des recettes
     * 
     * @Route("/cuisine/recette", name="cuisine_recettes_liste")
     * @IsGranted("ROLE_ADMIN")
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
     * Affiche 1 recette
     * 
     * @Route("/cuisine/recette/{id}", name="cuisine_recette")
     * @IsGranted("ROLE_ADMIN")
     *
     * @return Response
     */
    public function afficherRecette(RecetteRepository $repo, $id):Response {
        $element = "recette";
        $pagederesultat = "cuisine/recette.html.twig";
        return $this->afficherElement($id, $repo, $element, $pagederesultat);
    } 
    
    /**
     * Permet d'afficher le formulaire d'édition d'une recette
     *
     * @Route("/cuisine/recette/{id}/edit", name="cuisine_recette_edit")
     * @IsGranted("ROLE_ADMIN")
     * 
     * @return Response
     */
    public function editRecette(Recette $recette, Request $request, ObjectManager $manager, EtapeRecetteRepository $repoEtapeRecette, IngredientRepository $repoIngredient):Response {
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = $recette;
        $variables['classType'] = RecetteType::class;
        $variables['pagedebase'] = 'cuisine/recette_new.html.twig';
        $variables['pagederesultat'] = 'cuisine_recettes_liste';
        $variables['titre'] = "Edition de la recette : ".$recette->getNom().".";
        $variables['dependances'][1] = array('EtapesRecette' => 'Recette');
        $variables['dependances'][2] = array('Ingredients' => 'Recette');
        $variables['texteConfirmation'] = "La recette ### a bien été modifiée !";
        $variables['texteConfirmationEval']["###"] = '$element->getNom();';
        $variables['deletes'][1] = array('findBy' => 'recette', 'classEnfant' => 'EtapesRecette', 'repo' => $repoEtapeRecette );
        $variables['deletes'][2] = array('findBy' => 'recette', 'classEnfant' => 'Ingredient', 'repo' => $repoIngredient );
        return $this->formElement($variables);
    }

    /** GESTION DES STOCKAGES *************************************************************************************************************************************************************/
    /**
     * Création d'un stockage
     * 
     * @Route("/cuisine/stockage/new", name="cuisine_stockage_new")
     * @IsGranted("ROLE_ADMIN")
     *
     * @return Response
     */
    public function creerStockage(Request $request, ObjectManager $manager):Response {
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = new Stockage();
        $variables['classType'] = StockageType::class;
        $variables['pagedebase'] = 'cuisine/element_new.html.twig';
        $variables['pagederesultat'] = 'cuisine_stockages_liste';
        $variables['titre'] = "Création d'un stockage";
        $variables['texteConfirmation'] = "Le stockage ### a bien été créé !";
        $variables['texteConfirmationEval']["###"] = '$element->getNom();';
        
        return $this->formElement($variables);
    }

    /**
     * Affiche l'ensemble des stockages
     * 
     * @Route("/cuisine/stockage", name="cuisine_stockages_liste")
     * @IsGranted("ROLE_ADMIN")
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
     * @IsGranted("ROLE_ADMIN")
     * 
     * @return Response
     */
    public function editStockage(Stockage $stockage, Request $request, ObjectManager $manager):Response {
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = $stockage;
        $variables['classType'] = StockageType::class;
        $variables['pagedebase'] = 'cuisine/element_edit.html.twig';
        $variables['pagederesultat'] = 'cuisine_stockages_liste';
        $variables['titre'] = "Edition du stockage".$stockage->getNom().".";
        $variables['texteConfirmation'] = "Le stockage ### a bien été modifiée !";
        $variables['texteConfirmationEval']["###"] = '$element->getNom();';
        
        return $this->formElement($variables);
    }

    /** GESTION DES TYPES D'ALIMENTS ******************************************************************************************************************************************************/
    /**
     * Création d'un type d'aliment
     * 
     * @Route("/cuisine/typealiment/new", name="cuisine_typealiment_new")
     * @IsGranted("ROLE_ADMIN")
     *
     * @return Response
     */
    public function creerTypeAliment(Request $request, ObjectManager $manager):Response {
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = new TypeAliment();
        $variables['classType'] = TypeAlimentType::class;
        $variables['pagedebase'] = 'cuisine/element_new.html.twig';
        $variables['pagederesultat'] = 'cuisine_typesaliment_liste';
        $variables['titre'] = "Création d'un type d'aliment";
        $variables['texteConfirmation'] = "Le type daliment ### a bien été créé !";
        $variables['texteConfirmationEval']["###"] = '$element->getNom();';
        
        return $this->formElement($variables);
    } 
    
    /**
     * Affiche l'ensemble des types d'aliment
     * 
     * @Route("/cuisine/typesaliment", name="cuisine_typesaliment_liste")
     * @IsGranted("ROLE_ADMIN")
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
     * @IsGranted("ROLE_ADMIN")
     * 
     * @return Response
     */
    public function editTypeAliment(TypeAliment $typeAliment, Request $request, ObjectManager $manager):Response {
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = $typeAliment;
        $variables['classType'] = TypeAlimentType::class;
        $variables['pagedebase'] = 'cuisine/element_edit.html.twig';
        $variables['pagederesultat'] = 'cuisine_typesaliment_liste';
        $variables['titre'] = "Edition du type d'aliment".$typeAliment->getNom().".";
        $variables['texteConfirmation'] = "L'aliment ### a bien été modifiée !";
        $variables['texteConfirmationEval']["###"] = '$element->getNom();';
        
        return $this->formElement($variables);
    }

    /** GESTION DES UNITES ****************************************************************************************************************************************************************/
    /**
     * Création d'une unité
     * 
     * @Route("/cuisine/unite/new", name="cuisine_unite_new")
     * @IsGranted("ROLE_ADMIN")
     *
     * @return Response
     */
    public function creerUnite(Request $request, ObjectManager $manager):Response {
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = new Unite();
        $variables['classType'] = UniteType::class;
        $variables['pagedebase'] = 'cuisine/element_new.html.twig';
        $variables['pagederesultat'] = 'cuisine_unites_liste';
        $variables['titre'] = "Création d'une unité";
        $variables['texteConfirmation'] = "L'unité ### a bien été créé !";
        $variables['texteConfirmationEval']["###"] = '$element->getNom();';
        
        return $this->formElement($variables);
    }

    /**
     * Affiche l'ensemble des unités
     * 
     * @Route("/cuisine/unite", name="cuisine_unites_liste")
     * @IsGranted("ROLE_ADMIN")
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
     * @IsGranted("ROLE_ADMIN")
     * 
     * @return Response
     */
    public function editUnite(Unite $unite, Request $request, ObjectManager $manager):Response {
        $variables['request'] = $request;
        $variables['manager'] = $manager;
        $variables['element'] = $unite;
        $variables['classType'] = UniteType::class;
        $variables['pagedebase'] = 'cuisine/element_edit.html.twig';
        $variables['pagederesultat'] = 'cuisine_unites_liste';
        $variables['titre'] = "Edition de l'unité ".$unite->getNom().".";
        $variables['texteConfirmation'] = "L'unité ### a bien été modifiée !";
        $variables['texteConfirmationEval']["###"] = '$element->getNom();';
        
        return $this->formElement($variables);
    }
}
