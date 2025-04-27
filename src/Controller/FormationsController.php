<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur pour la gestion des formations sur le front-office.
 *
 * Permet d'afficher la liste, trier, filtrer et consulter les détails d'une formation.
 * 
 * @author emds
 */
class FormationsController extends AbstractController
{   
    /**
     * Chemin du template utilisé pour l'affichage de la liste des formations.
     */
    private const TEMPLATE_FORMATIONS = 'pages/formations.html.twig';

    /**
     * Chemin du template utilisé pour l'affichage du détail d'une formation.
     */
    private const TEMPLATE_FORMATION = 'pages/formation.html.twig';

    /**
     * Repository pour accéder aux formations.
     *
     * @var FormationRepository
     */
    private $formationRepository;
    
    /**
     * Repository pour accéder aux catégories.
     *
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     * Constructeur pour injecter les repositories Formation et Categorie.
     *
     * @param FormationRepository $formationRepository
     * @param CategorieRepository $categorieRepository
     */
    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository)
    {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }
    
    /**
     * Affiche toutes les formations disponibles avec leurs catégories.
     *
     * @return Response
     */
    #[Route('/formations', name: 'formations')]
    public function index(): Response
    {
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::TEMPLATE_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    /**
     * Tri les formations selon un champ spécifique (titre, playlist, date...).
     *
     * @param string $champ Le champ sur lequel trier
     * @param string $ordre L'ordre de tri (ASC ou DESC)
     * @param string $table Table jointe si nécessaire (playlist ou categories)
     * @return Response
     */
    #[Route('/formations/tri/{champ}/{ordre}/{table}', name: 'formations.sort')]
    public function sort($champ, $ordre, $table = ""): Response
    {
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::TEMPLATE_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    /**
     * Filtre les formations selon un critère donné (titre, playlist, catégorie).
     *
     * @param string $champ Champ à filtrer
     * @param Request $request Objet contenant la valeur à rechercher
     * @param string $table Table jointe si nécessaire
     * @return Response
     */
    #[Route('/formations/recherche/{champ}/{table}', name: 'formations.findallcontain')]
    public function findAllContain($champ, Request $request, $table = ""): Response
    {
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::TEMPLATE_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    /**
     * Affiche le détail d'une seule formation sélectionnée.
     *
     * @param int $id ID de la formation
     * @return Response
     */
    #[Route('/formations/formation/{id}', name: 'formations.showone')]
    public function showOne($id): Response
    {
        $formation = $this->formationRepository->find($id);
        return $this->render(self::TEMPLATE_FORMATION, [
            'formation' => $formation
        ]);
    }
}
