<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Controller;

use App\Entity\Addressee;
use App\Repository\AddresseeRepository;
use App\Repository\PostRepository;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AddresseeControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/addressee/');

        $this->assertResponseIsSuccessful();

        $this->assertCount(
            AddresseeRepository::NUM_ITEMS + 1,
            $crawler->filter('tr'),
            'The homepage displays the right number of posts.'
        );
    }

    public function testAjaxSearch(): void
    {
        $client = static::createClient();
        $client->xmlHttpRequest('GET', '/addressee/search', ['q' => 'ernathy']);

        $results = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertCount(1, $results);
        $this->assertSame('janice.thiel@greenfelder.biz', $results[0]['email']);
    }

    public function testCreateEntry(): void
    {
        $faker = Factory::create();

        $firstName = uniqid();
        $lastName = $faker->lastName;

        $client = static::createClient();
        $client->request('GET', '/addressee/item/new');
        $client->submitForm('Create entry', [
            'addressee[firstname]' => $firstName,
            'addressee[lastname]' => $lastName,
            'addressee[phone]' => $faker->phoneNumber,
            'addressee[email]' => $faker->email,
            'addressee[birthday][day]' => 1,
            'addressee[birthday][month]' => 1,
            'addressee[birthday][year]' => 2001,
            'addressee[city]' => 1,
            'addressee[house]' => $faker->buildingNumber,
            'addressee[street]' => $faker->streetName,
            'addressee[country]' => 1,
            'addressee[zip]' => $faker->postcode,
        ]);

        $this->assertResponseRedirects('/addressee/', Response::HTTP_FOUND);

        /** @var \App\Entity\Addressee $item */
        $item = self::$container->get(AddresseeRepository::class)->findBySearchQuery($firstName)[0];
        $this->assertNotNull($item);
        $this->assertSame($lastName, $item->getLastname());
    }

    public function testAdminShowPost(): void
    {
        $client = static::createClient([]);
        $client->request('GET', '/addressee/1');

        $this->assertResponseIsSuccessful();
    }
}
