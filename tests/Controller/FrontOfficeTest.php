<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontOfficeTest extends WebTestCase
{
    public function testHomePageIsAccessible()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h3'); // Vérifie juste qu'un titre existe
    }

    public function testFormationsPageAccessible()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h5.text-info'); // Vérifie qu'une formation est affichée
    }

    public function testFormationsPageHasSearchField()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('input[name="recherche"]'); // Vérifie qu'un champ de recherche existe
    }
    
    public function testFormationDetailPage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');

        $this->assertResponseIsSuccessful();

        // Trouver le premier lien sur une miniature et cliquer
        $link = $crawler->filter('a[href*="/formations/formation/"]')->first()->link();
        $client->click($link);

        // Vérifier qu'on est sur une page de détail
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h5.text-info'); // Vérifie qu'on a un titre de formation sur la page
    }

}
