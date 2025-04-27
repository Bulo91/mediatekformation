<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use App\Repository\CategorieRepository;
use App\Repository\PlaylistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/formations')]
class AdminFormationController extends AbstractController
{
    private FormationRepository $formationRepository;
    private CategorieRepository $categorieRepository;
    private PlaylistRepository $playlistRepository;

    public function __construct(
        FormationRepository $formationRepository,
        CategorieRepository $categorieRepository,
        PlaylistRepository $playlistRepository
    ) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
        $this->playlistRepository = $playlistRepository;
    }

    #[Route('/', name: 'admin.formations')]
    public function index(): Response
    {
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render('pages/admin_formations.html.twig', [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    #[Route('/add', name: 'admin.formations.add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($formation);
            $em->flush();

            return $this->redirectToRoute('admin.formations');
        }

        return $this->render('pages/formation_form.html.twig', [
            'form' => $form->createView(),
            'isEdit' => false
        ]);
    }
    
    #[Route('/edit/{id}', name: 'admin.formations.edit')]
    public function edit(Formation $formation, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('admin.formations');
        }

        return $this->render('pages/formation_form.html.twig', [
            'form' => $form->createView(),
            'isEdit' => true
        ]);
    }

    #[Route('/delete/{id}', name: 'admin.formations.delete')]
    public function delete(Formation $formation, EntityManagerInterface $em): Response
    {
        $em->remove($formation);
        $em->flush();

        return $this->redirectToRoute('admin.formations');
    }

}
