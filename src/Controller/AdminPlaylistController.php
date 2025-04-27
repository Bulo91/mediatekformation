<?php

namespace App\Controller;

use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/playlists')]
class AdminPlaylistController extends AbstractController
{
    private PlaylistRepository $playlistRepository;
    private FormationRepository $formationRepository;

    public function __construct(
        PlaylistRepository $playlistRepository,
        FormationRepository $formationRepository
    ) {
        $this->playlistRepository = $playlistRepository;
        $this->formationRepository = $formationRepository;
    }

    #[Route('/', name: 'admin.playlists')]
    public function index(): Response
    {
        $playlists = $this->playlistRepository->findAll();
        return $this->render('pages/admin_playlists.html.twig', [
            'playlists' => $playlists
        ]);
    }

    #[Route('/add', name: 'admin.playlists.add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $playlist = new Playlist();
        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($playlist);
            $em->flush();

            return $this->redirectToRoute('admin.playlists');
        }

        return $this->render('pages/playlist_form.html.twig', [
            'form' => $form->createView(),
            'isEdit' => false
        ]);
    }

    #[Route('/edit/{id}', name: 'admin.playlists.edit')]
    public function edit(Playlist $playlist, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('admin.playlists');
        }

        return $this->render('pages/playlist_form.html.twig', [
            'form' => $form->createView(),
            'isEdit' => true,
            'playlistformations' => $playlist->getFormations()
        ]);
    }

    #[Route('/delete/{id}', name: 'admin.playlists.delete')]
    public function delete(Playlist $playlist, EntityManagerInterface $em): Response
    {
        if ($playlist->getFormations()->count() > 0) {
            $this->addFlash('error', 'Impossible de supprimer une playlist contenant des formations.');
        } else {
            $em->remove($playlist);
            $em->flush();
        }

        return $this->redirectToRoute('admin.playlists');
    }
}
