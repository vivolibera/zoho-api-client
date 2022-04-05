<?php

namespace ZohoApiClient\Entities;

class Contact
{
    private ?int $id;
    private string $firstName;
    private string $lastName;
    private ?string $salutation;
    private ?string $mailingStreet;
    private ?string $mailingZip;
    private ?string $mailingCity;
    private ?string $mailingCountry;
    private ?string $email;
    private ?string $phone;
    private ?string $mobile;
    private ?Account $account;
    private array $rawData;

    /**
     * @param int|null $id
     * @param string $firstName
     * @param string $lastName
     * @param string|null $salutation
     * @param string|null $mailingStreet
     * @param string|null $mailingZip
     * @param string|null $mailingCity
     * @param string|null $mailingCountry
     * @param string|null $email
     * @param string|null $phone
     * @param string|null $mobile
     * @param Account|null $account
     * @param array $rawData
     */
    public function __construct(
        ?int $id,
        string $firstName,
        string $lastName,
        ?string $salutation,
        ?string $mailingStreet,
        ?string $mailingZip,
        ?string $mailingCity,
        ?string $mailingCountry,
        ?string $email,
        ?string $phone,
        ?string $mobile,
        ?Account $account,
        array $rawData = []
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->salutation = $salutation;
        $this->mailingStreet = $mailingStreet;
        $this->mailingZip = $mailingZip;
        $this->mailingCity = $mailingCity;
        $this->mailingCountry = $mailingCountry;
        $this->email = $email;
        $this->phone = $phone;
        $this->mobile = $mobile;
        $this->account = $account;
        $this->rawData = $rawData;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string|null
     */
    public function getSalutation(): ?string
    {
        return $this->salutation;
    }

    /**
     * @return string|null
     */
    public function getMailingStreet(): ?string
    {
        return $this->mailingStreet;
    }

    /**
     * @return string|null
     */
    public function getMailingZip(): ?string
    {
        return $this->mailingZip;
    }

    /**
     * @return string|null
     */
    public function getMailingCity(): ?string
    {
        return $this->mailingCity;
    }

    /**
     * @return string|null
     */
    public function getMailingCountry(): ?string
    {
        return $this->mailingCountry;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @return string|null
     */
    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    /**
     * @return Account|null
     */
    public function getAccount(): ?Account
    {
        return $this->account;
    }

    /**
     * @return array
     */
    public function getRawData(): array
    {
        return $this->rawData;
    }
}