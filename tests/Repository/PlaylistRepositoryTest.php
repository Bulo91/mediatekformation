<?php

namespace App\Tests\Repository;

use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PlaylistRepositoryTest extends KernelTestCase
{
    public function testFindAllOrderByName()
    {
        self::bootKernel();
        $playlistRepository = static::getContainer()->get(PlaylistRepository::class);

        // Créer une nouvelle playlist pour tester
        $playlist = new Playlist();
        $playlist->setName('Test Playlist Order');
        $playlist->setDescription('Description de test');

        $entityManager = static::getContainer()->get('doctrine')->getManager();
        $entityManager->persist($playlist);
        $entityManager->flush();

        $playlists = $playlistRepository->findAllOrderByName('ASC');
        $this->assertNotEmpty($playlists);

        $found = false;
        foreach ($playlists as $pl) {
            if ($pl->getName() === 'Test Playlist Order') {
                $found = true;
            }
        }

        $this->assertTrue($found, 'La playlist "Test Playlist Order" n\'a pas été trouvée.');
    }

    public function testFindByContainValue()
    {
        self::bootKernel();
        $playlistRepository = static::getContainer()->get(PlaylistRepository::class);

        $playlists = $playlistRepository->findByContainValue('name', 'Test Playlist Order');
        $this->assertNotEmpty($playlists);
        $this->assertEquals('Test Playlist Order', $playlists[0]->getName());
    }
}
