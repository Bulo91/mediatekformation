<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/categories')]
class AdminCategorieController extends AbstractController
{
    private CategorieRepository $categorieRepository;
    private FormationRepository $formationRepository;

    public function __construct(CategorieRepository $categorieRepository, FormationRepository $formationRepository)
    {
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRepository;
    }

    #[Route('/', name: 'admin.categories')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier l’unicité du nom
            $existing = $this->categorieRepository->findOneBy(['name' => $categorie->getName()]);
            if ($existing) {
                $this->addFlash('error', 'Cette catégorie existe déjà.');
            } else {
                $em->persist($categorie);
                $em->flush();
                $this->addFlash('success', 'Catégorie ajoutée avec succès.');
                return $this->redirectToRoute('admin.categories');
            }
        }

        $categories = $this->categorieRepository->findAll();

        return $this->render('pages/admin_categories.html.twig', [
            'categories' => $categories,
            'form' => $form->createView()
        ]);
    }

    #[Route('/delete/{id}', name: 'admin.categories.delete')]
    public function delete(Categorie $categorie, EntityManagerInterface $em): Response
    {
        if ($categorie->getFormations()->count() > 0) {
            $this->addFlash('error', 'Impossible de supprimer cette catégorie, elle est utilisée par des formations.');
        } else {
            $em->remove($categorie);
            $em->flush();
            $this->addFlash('success', 'Catégorie supprimée avec succès.');
        }

        return $this->redirectToRoute('admin.categories');
    }
}
