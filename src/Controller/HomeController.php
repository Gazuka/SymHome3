<?php

namespace App\Controller;

use App\Entity\Boite;
use App\Service\Fulfillment;
use App\Repository\BoiteRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends OutilsController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(Fulfillment $fulfillment, ObjectManager $manager)
    {
        $fulfillment->index();

        //On ajoute des variables selon la page de redirection
        switch($fulfillment->Action)
        {
            case 'liste_boites':                
                //$age = $fulfillment->fulfillmentRecupContext('patate', 'age');
                //$repo = $manager->getRepository(Boite::class);
                //return $this->listingBoites($repo);
                return $this->listeBoites($manager);
            break;
            /*case 'liste_typealiment':
                //Affiche la liste des type d'aliment
                $repo = $manager->getRepository(TypeAliment::class);
                return $this->listingTypesAliment($repo);
            break;
            case 'new_typealiment';
                $nom = $fulfillment->fulfillmentRecupParam('nom');
                $typealiment = new TypeAliment();
                $typealiment->setNom($nom);
                $manager->persist($typealiment);
                $manager->flush();
                return $this->render('cuisine/newtypealiment.'.$this->format.'.twig', [
                    'nom' => $nom
                ]);
            break;*/
            default :
                //$this->format = "html";
                return $this->render('home/index.json.twig');
            break;
        }

        /*return $this->render('home/index.json.twig', [
            'controller_name' => 'HomeController',
        ]);*/
    }

    /**
     * Affiche l'ensemble des boites
     *
     * @return Response
     */
    public function listeBoites(ObjectManager $manager):Response {
        $repo = $manager->getRepository(Boite::class);
        $elements = "boites";
        $titre = "Listing des boites";
        $pagederesultat = "home/boites_liste.json.twig";
        return $this->recupererElements($repo, $elements, $titre, $pagederesultat);
    }
}
