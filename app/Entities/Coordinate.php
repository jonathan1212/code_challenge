<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'coordinates')]
class Coordinate
{
  #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
  private int $id;

  #[ORM\Column(type: 'string')]
  private string $latitude;

  #[ORM\Column(type: 'string')]
  private string $longitude;

  #[ORM\OneToOne(inversedBy: 'coordinate', targetEntity: Address::class)]
  #[ORM\JoinColumn(name: 'address_id', referencedColumnName: 'id')]
  private Address $address;

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
  public function getLatitude(): string
  {
    return $this->latitude;
  }

  /**
   * @param string $latitude
   */
  public function setLatitude(string $latitude): void
  {
    $this->latitude = $latitude;
  }

  /**
   * @return string
   */
  public function getLongitude(): string
  {
    return $this->longitude;
  }

  /**
   * @param string $longitude
   */
  public function setLongitude(string $longitude): void
  {
    $this->longitude = $longitude;
  }

  /**
   * @return Address
   */
  public function getAddress(): Address
  {
    return $this->address;
  }

  /**
   * @param Address $address
   */
  public function setAddress(Address $address): void
  {
    $this->address = $address;
  }



}
