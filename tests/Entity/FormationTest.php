<?php

namespace App\Tests\Entity;

use App\Entity\Formation;
use PHPUnit\Framework\TestCase;

class FormationTest extends TestCase
{
    public function testGetPublishedAtString()
    {
        $formation = new Formation();

        // Cas 1 : publishedAt est null
        $this->assertEquals('', $formation->getPublishedAtString());

        // Cas 2 : publishedAt a une date précise
        $date = new \DateTime('2024-04-26');
        $formation->setPublishedAt($date);

        $this->assertEquals('26/04/2024', $formation->getPublishedAtString());
    }
}
