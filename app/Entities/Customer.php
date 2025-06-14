<?php

namespace App\Entities;

use App\Enum\GenderEnum;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity]
#[ORM\Table(name: 'customers')]
#[UniqueEntity('email')]
class Customer
{
  #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
  private int $id;

  #[ORM\Column(enumType: GenderEnum::class)]
  private GenderEnum $gender;

  #[ORM\Column(type: 'string')]
  private string $firstName;

  #[ORM\Column(type: 'string')]
  private string $lastName;

  #[ORM\Column(type: 'string', unique: true)]
  private string $email;

  #[ORM\Column(type: 'string')]
  private string $username;

  #[ORM\Column(type: 'string')]
  private string $phone;

  #[ORM\Column(type: 'string')]
  private string $cell;

  #[ORM\Column(type: 'string')]
  private string $nat;

  #[ORM\Column(type: 'string')]
  private string $password; // md5 hashed

  #[ORM\OneToOne(mappedBy: 'customer', targetEntity: Address::class, cascade: ['persist', 'remove'])]
  private ?Address $address = null;


  /**
   * @return int
   */
  public function getId(): int
  {
    return $this->id;
  }

  /**
   * @return GenderEnum
   */
  public function getGender(): GenderEnum
  {
    return $this->gender;
  }

  /**
   * @param GenderEnum $gender
   */
  public function setGender(GenderEnum $gender): void {
    $this->gender = $gender;
  }

  /**
   * @return string
   */
  public function getFirstName(): string
  {
    return $this->firstName;
  }

  public function getFullName(): string
  {
    return $this->firstName . ' ' . $this->lastName;
  }

  /**
   * @param string $firstName
   */
  public function setFirstName(string $firstName): void
  {
    $this->firstName = $firstName;
  }

  /**
   * @return string
   */
  public function getLastName(): string
  {
    return $this->lastName;
  }

  /**
   * @param string $lastName
   */
  public function setLastName(string $lastName): void
  {
    $this->lastName = $lastName;
  }

  /**
   * @return string
   */
  public function getEmail(): string
  {
    return $this->email;
  }

  /**
   * @param string $email
   */
  public function setEmail(string $email): void
  {
    $this->email = $email;
  }

  /**
   * @return string
   */
  public function getUsername(): string
  {
    return $this->username;
  }

  /**
   * @param string $username
   */
  public function setUsername(string $username): void
  {
    $this->username = $username;
  }

  /**
   * @return string
   */
  public function getPhone(): string
  {
    return $this->phone;
  }

  /**
   * @param string $phone
   */
  public function setPhone(string $phone): void
  {
    $this->phone = $phone;
  }

  /**
   * @return string
   */
  public function getCell(): string
  {
    return $this->cell;
  }

  /**
   * @param string $cell
   */
  public function setCell(string $cell): void
  {
    $this->cell = $cell;
  }

  /**
   * @return string
   */
  public function getNat(): string
  {
    return $this->nat;
  }

  /**
   * @param string $nat
   */
  public function setNat(string $nat): void
  {
    $this->nat = $nat;
  }

  /**
   * @return string
   */
  public function getPassword(): string
  {
    return $this->password;
  }

  /**
   * @param string $password
   */
  public function setPassword(string $password): void
  {
    $this->password = $password;
  }

  /**
   * @return Address
   */
  public function getAddress(): ?Address
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
