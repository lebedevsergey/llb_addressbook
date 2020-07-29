<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AddresseeRepository")
 * @ORM\Table(name="addressees")
 * @UniqueEntity(fields={"id"})
 *
 */
class Addressee
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    private $lastname;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Country")
     * @ORM\JoinColumn(nullable=false)
     */
    private $country;

    /**
     * @var City
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\City")
     * @ORM\JoinColumn(nullable=false)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     * @Assert\Length(max=255)
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     * @Assert\Length(max=64)
     */
    private $house;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     * @Assert\Length(max=64)
     */
    private $zip;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     * @Assert\Length(max=64)
     */
    private $phone_number;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     * @Assert\Length(max=64)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $birthday;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Assert\Length(max=255)
     */
    private $pictureurl;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $data): void
    {
        $this->firstname = $data;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $data): void
    {
        $this->lastname = $data;
    }

    public function getBirthday(): ?\DateTime
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTime $data): void
    {
        $this->birthday = $data;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $data): void
    {
        $this->email = $data;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setZip(string $data): void
    {
        $this->zip = $data;
    }

    public function getPhone(): ?string
    {
        return $this->phone_number;
    }

    public function setPhone(string $data): void
    {
        $this->phone_number = $data;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $data): void
    {
        $this->street = $data;
    }


    public function getHouse(): ?string
    {
        return $this->house;
    }

    public function setHouse(string $data): void
    {
        $this->house = $data;
    }

    public function getPictureUrl(): ?string
    {
        return $this->pictureurl;
    }

    public function setPictureUrl(string $data): void
    {
        $this->pictureurl = $data;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(City $data): void
    {
        $this->city = $data;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(Country $data): void
    {
        $this->country = $data;
    }
}
