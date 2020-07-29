<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DataFixtures;

use App\Entity\Addressee;
use App\Entity\City;
use App\Entity\Country;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker\Factory;

class AppFixtures extends Fixture
{
    const ADDRESSEES_NUM = 100;
    const COUNTRY_NUM = 20;
    const CITY_NUM = 50;

    private $passwordEncoder;
    private $slugger;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, SluggerInterface $slugger)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
        $this->loadCity($manager);
        $this->loadCountry($manager);
        $this->loadAddressees($manager);
    }

    private function loadCountry(ObjectManager $manager): void
    {
        $takenNames = [];
        for ($i=1; $i<=self::COUNTRY_NUM; $i++) {
            $faker = Factory::create();
            $item = new Country();

            while (true) {
                $name = $faker->city;
                if (!in_array($name, $takenNames)) {
                    break;
                }
            }
            $takenNames[] = $name;

            $item->setName($name);
            $manager->persist($item);
            $this->addReference(get_class($item) . '_' . $i, $item);
        }

        $manager->flush();
    }

    private function loadCity(ObjectManager $manager): void
    {
        $takenNames = [];
        for ($i=1; $i<=self::CITY_NUM; $i++) {
            $faker = Factory::create();
            $item = new City();

            while (true) {
                $name = $faker->city;
                if (!in_array($name, $takenNames)) {
                    break;
                }
            }
            $takenNames[] = $name;

            $item->setName($name);
            $manager->persist($item);
            $this->addReference(get_class($item) . '_' . $i, $item);
        }

        $manager->flush();
    }

    private function loadAddressees(ObjectManager $manager): void
    {
        for ($i=0; $i<self::ADDRESSEES_NUM; $i++) {
            $item = new Addressee();
            $this->fillAddressee($item);
            $manager->persist($item);
        }

        $manager->flush();
    }

    private function loadUsers(ObjectManager $manager): void
    {
        foreach ($this->getUserData() as [$fullname, $username, $password, $email, $roles]) {
            $user = new User();
            $user->setFullName($fullname);
            $user->setUsername($username);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setEmail($email);
            $user->setRoles($roles);

            $manager->persist($user);
            $this->addReference($username, $user);
        }

        $manager->flush();
    }

    private function fillAddressee(Addressee $item)
    {
        $faker = Factory::create();
        $item->setFirstname($faker->firstName);
        $item->setLastname($faker->lastName);
        $item->setBirthday($faker->dateTime);
        $item->setStreet($faker->streetName);
        $item->setHouse($faker->buildingNumber);
        $item->setZip($faker->postcode);
        $item->setEmail($faker->email);
        $item->setPhone($faker->phoneNumber);
        $item->setCity($this->getReference(City::class.'_'.rand(1, self::CITY_NUM)));
        $item->setCountry($this->getReference(Country::class.'_'.rand(1, self::COUNTRY_NUM)));
    }

    private function getUserData(): array
    {
        return [
            // $userData = [$fullname, $username, $password, $email, $roles];
            ['Jane Doe', 'jane_admin', 'kitten', 'jane_admin@symfony.com', ['ROLE_ADMIN']],
            ['Tom Doe', 'tom_admin', 'kitten', 'tom_admin@symfony.com', ['ROLE_ADMIN']],
            ['John Doe', 'john_user', 'kitten', 'john_user@symfony.com', ['ROLE_USER']],
        ];
    }
}
