<?php

namespace App\Tests\Repository;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FormationRepositoryTest extends KernelTestCase
{
    public function testFindAllOrderBy()
    {
        self::bootKernel();
        $formationRepository = static::getContainer()->get(FormationRepository::class);

        // Créer une nouvelle formation pour tester
        $formation = new Formation();
        $formation->setTitle('Test Formation Order');
        $formation->setPublishedAt(new \DateTime('2024-04-25'));

        $entityManager = static::getContainer()->get('doctrine')->getManager();
        $entityManager->persist($formation);
        $entityManager->flush();

        $formations = $formationRepository->findAllOrderBy('title', 'ASC');
        $this->assertNotEmpty($formations);

        $found = false;
        foreach ($formations as $form) {
            if ($form->getTitle() === 'Test Formation Order') {
                $found = true;
            }
        }

        $this->assertTrue($found, 'La formation "Test Formation Order" n\'a pas été trouvée.');
    }

    public function testFindByContainValue()
    {
        self::bootKernel();
        $formationRepository = static::getContainer()->get(FormationRepository::class);

        $formations = $formationRepository->findByContainValue('title', 'Test Formation Order');
        $this->assertNotEmpty($formations);
        $this->assertEquals('Test Formation Order', $formations[0]->getTitle());
    }
}
