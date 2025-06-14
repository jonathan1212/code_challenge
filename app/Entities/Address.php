<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use others\Timezone;

#[ORM\Entity]
#[ORM\Table(name: 'addresses')]
class Address
{
  #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
  private int $id;

  #[ORM\Column(type: 'string')]
  private string $streetName;

  #[ORM\Column(type: 'integer')]
  private int $streetNumber;

  #[ORM\Column(type: 'string')]
  private string $city;

  #[ORM\Column(type: 'string')]
  private string $state;

  #[ORM\Column(type: 'string')]
  private string $country;

  #[ORM\Column(type: 'string')]
  private string $postcode;

  #[ORM\OneToOne(inversedBy: 'address', targetEntity: Customer::class)]
  #[ORM\JoinColumn(name: 'customer_id', referencedColumnName: 'id')]
  private Customer $customer;

  #[ORM\OneToOne(mappedBy: 'address', targetEntity: Coordinate::class, cascade: ['persist', 'remove'])]
  private ?Coordinate $coordinate = null;



  /**
   * @return int
   */
  public function getId(): int
  {
    return $this->id;
  }


  /**
   * @return string
   */
  public function getStreetName(): string
  {
    return $this->streetName;
  }

  /**
   * @param string $streetName
   */
  public function setStreetName(string $streetName): void
  {
    $this->streetName = $streetName;
  }

  /**
   * @return int
   */
  public function getStreetNumber(): int
  {
    return $this->streetNumber;
  }

  /**
   * @param int $streetNumber
   */
  public function setStreetNumber(int $streetNumber): void
  {
    $this->streetNumber = $streetNumber;
  }

  /**
   * @return string
   */
  public function getCity(): string
  {
    return $this->city;
  }

  /**
   * @param string $city
   */
  public function setCity(string $city): void
  {
    $this->city = $city;
  }

  /**
   * @return string
   */
  public function getState(): string
  {
    return $this->state;
  }

  /**
   * @param string $state
   */
  public function setState(string $state): void
  {
    $this->state = $state;
  }

  /**
   * @return string
   */
  public function getCountry(): string
  {
    return $this->country;
  }

  /**
   * @param string $country
   */
  public function setCountry(string $country): void
  {
    $this->country = $country;
  }

  /**
   * @return string
   */
  public function getPostcode(): string
  {
    return $this->postcode;
  }

  /**
   * @param string $postcode
   */
  public function setPostcode(string $postcode): void
  {
    $this->postcode = $postcode;
  }

  /**
   * @return Customer
   */
  public function getCustomer(): Customer
  {
    return $this->customer;
  }

  /**
   * @param Customer $customer
   */
  public function setCustomer(Customer $customer): void
  {
    $this->customer = $customer;
  }

  /**
   * @return Coordinate
   */
  public function getCoordinate(): ?Coordinate
  {
    return $this->coordinate;
  }

  /**
   * @param Coordinate $coordinate
   */
  public function setCoordinate(Coordinate $coordinate): void
  {
    $this->coordinate = $coordinate;
  }


}
