<?php

namespace ZohoApiClient\Entities;

class Account
{
    public string $id;
    public string $name;
    public string $address;
    public string $zipCode;
    public string $city;
    public string $country;
    public string $phone;
    public array $rawData;

    public function __construct(
        $id,
        $name = "",
        $address = "",
        $zipCode = "",
        $city = "",
        $country = "",
        $phone = "",
        $rawData = []
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->address = $address;
        $this->zipCode = $zipCode;
        $this->city = $city;
        $this->country = $country;
        $this->phone = $phone;
        $this->rawData = $rawData;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Account
     */
    public function setId(string $id): Account
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Account
     */
    public function setName(string $name): Account
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return Account
     */
    public function setAddress(string $address): Account
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    /**
     * @param string $zipCode
     * @return Account
     */
    public function setZipCode(string $zipCode): Account
    {
        $this->zipCode = $zipCode;
        return $this;
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
     * @return Account
     */
    public function setCity(string $city): Account
    {
        $this->city = $city;
        return $this;
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
     * @return Account
     */
    public function setCountry(string $country): Account
    {
        $this->country = $country;
        return $this;
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
     * @return Account
     */
    public function setPhone(string $phone): Account
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return array
     */
    public function getRawData(): array
    {
        return $this->rawData;
    }

    /**
     * @param array $rawData
     * @return Account
     */
    public function setRawData(array $rawData): Account
    {
        $this->rawData = $rawData;
        return $this;
    }
}