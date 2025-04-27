<?php

namespace App\Tests\Entity;

use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FormationValidationTest extends KernelTestCase
{
    public function getFormation(): Formation
    {
        $formation = new Formation();
        $formation->setTitle('Test Formation');
        $formation->setDescription('Description test');
        $formation->setVideoId('123456');

        // Fixer publishedAt aujourd'hui à 00:00:00
        $todayMidnight = (new \DateTime())->setTime(0, 0, 0);
        $formation->setPublishedAt($todayMidnight);

        return $formation;
    }


    public function assertHasErrors(Formation $formation, int $number = 0)
    {
        self::bootKernel();
        $errors = static::getContainer()->get('validator')->validate($formation);
        $messages = [];
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath().' => '.$error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidPublishedAt()
    {
        $formation = $this->getFormation();
        $this->assertHasErrors($formation, 0);
    }

    public function testInvalidPublishedAt()
    {
        $formation = $this->getFormation();
        // Mettre une date dans le futur
        $formation->setPublishedAt((new \DateTime())->modify('+1 day'));
        $this->assertHasErrors($formation, 1);
    }
}
