<?php

namespace App\Tests\Repository;

use App\Entity\Categorie;
use App\Entity\Formation;
use App\Entity\Playlist;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategorieRepositoryTest extends KernelTestCase
{
    public function testFindAllForOnePlaylist()
    {
        self::bootKernel();
        $entityManager = static::getContainer()->get('doctrine')->getManager();
        $categorieRepository = static::getContainer()->get(CategorieRepository::class);

        // Créer une catégorie
        $categorie = new Categorie();
        $categorie->setName('Test Categorie');
        $entityManager->persist($categorie);

        // Créer une playlist
        $playlist = new Playlist();
        $playlist->setName('Test Playlist for Categorie');
        $playlist->setDescription('Description de test');
        $entityManager->persist($playlist);

        // Créer une formation liée à cette playlist et catégorie
        $formation = new Formation();
        $formation->setTitle('Formation Test');
        $formation->setPublishedAt(new \DateTime('2024-04-26'));
        $formation->setPlaylist($playlist);
        $formation->addCategory($categorie);
        $entityManager->persist($formation);

        $entityManager->flush();

        // Tester que la catégorie est retrouvée via la playlist
        $categories = $categorieRepository->findAllForOnePlaylist($playlist->getId());
        $this->assertNotEmpty($categories);
        $this->assertEquals('Test Categorie', $categories[0]->getName());
    }
}
