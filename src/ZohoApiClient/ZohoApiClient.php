<?php

namespace ZohoApiClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use ZohoApiClient\Entities\Account;
use ZohoApiClient\Entities\Contact;

class ZohoApiClient
{
    public Client $accountsClient;
    public ?Client $apiClient = null;

    public function __construct(
        $zohoDomain = "us",
        $clientId = null,
        $clientSecret = null,
        $refreshToken = null
    ) {
        $this->accountsClient = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://accounts.zoho.' . $zohoDomain . '/',
            // You can set any number of default request options.
            'timeout'  => 2.0,
            'headers' => [
                'Accept' => 'application/json'
            ]
        ]);

        if(!is_null($refreshToken)) {
            $accessToken = $this->getOAuthTokenByRefreshToken(
                $clientId,
                $clientSecret,
                $refreshToken
            );

            $this->apiClient = new Client([
                // Base URI is used with relative requests
                'base_uri' => 'https://www.zohoapis.' . $zohoDomain . '/',
                // You can set any number of default request options.
                'timeout'  => 2.0,
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => sprintf('Zoho-oauthtoken %s', $accessToken)
                ]
            ]);
        }
    }

    public function getOAuthTokenByCode($clientId, $clientSecret, $code) : array
    {
        $res = $this->accountsClient->post('/oauth/v2/token', [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'code' => $code
            ]
        ]);

        if($res->getStatusCode() != 200) {
            throw new ZohoApiClientException("getOAuthToken() => status code != 200");
        }

        $data = json_decode(
            (string) $res->getBody(),
            true
        );

        if(isset($data['error']) && $data['error'] == 'invalid_code') {
            throw new ZohoApiClientException("getOAuthToken() => invalid client");
        }

        if(isset($data['error']) && $data['error'] == 'invalid_code') {
            throw new ZohoApiClientException("getOAuthToken() => invalid code");
        }

        if(isset($data['error']) && $data['error'] == 'invalid_client_secret') {
            throw new ZohoApiClientException("getOAuthToken() => invalid client secret");
        }

        return [
            "access_token" => $data['access_token'],
            "refresh_token" => $data['refresh_token']
        ];
    }

    public function getOAuthTokenByRefreshToken($clientId, $clientSecret, $refreshToken) : string
    {
        $res = $this->accountsClient->post('/oauth/v2/token', [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'refresh_token' => $refreshToken
            ]
        ]);

        if($res->getStatusCode() != 200) {
            throw new ZohoApiClientException("getOAuthToken() => status code != 200");
        }

        $data = json_decode(
            (string) $res->getBody(),
            true
        );

        if(isset($data['error']) && $data['error'] == 'invalid_code') {
            throw new ZohoApiClientException("getOAuthToken() => invalid client");
        }

        if(isset($data['error']) && $data['error'] == 'invalid_code') {
            throw new ZohoApiClientException("getOAuthToken() => invalid code");
        }

        if(isset($data['error']) && $data['error'] == 'invalid_client_secret') {
            throw new ZohoApiClientException("getOAuthToken() => invalid client secret");
        }

        return $data['access_token'];
    }

    /**
     * @return Account[]
     * @throws ZohoApiClientException
     * @throws GuzzleException
     */
    public function getAccounts(): array {
        if($this->apiClient == null) {
            throw new ZohoApiClientException("getAccounts() => refresh token not set!");
        }

        $res = $this->apiClient->get('/crm/v2/accounts');

        if($res->getStatusCode() != 200) {
            throw new ZohoApiClientException("getCustomers() => status code != 200");
        }

        $data = json_decode((string) $res->getBody(), true);

        $customers = [];
        foreach($data['data'] as $customer) {
            $c = new Account($customer['id']);
            $c->setRawData($customer);

            if(isset($customer['Account_Name'])) {
                $c->setName($customer['Account_Name']);
            }

            if(isset($customer['Billing_Street'])) {
                $c->setAddress($customer['Billing_Street']);
            }

            if(isset($customer['Billing_Code'])) {
                $c->setZipCode($customer['Billing_Code']);
            }

            if(isset($customer['Billing_City'])) {
                $c->setCity($customer['Billing_City']);
            }

            if(isset($customer['Billing_Country'])) {
                $c->setCountry($customer['Billing_Country']);
            }

            if(isset($customer['Phone'])) {
                $c->setPhone($customer['Phone']);
            }

            $customers[] = $c;
        }

        return $customers;
    }

    /**
     * @return Contact[]
     * @throws ZohoApiClientException
     * @throws GuzzleException
     */
    public function getContacts(): array {
        if($this->apiClient == null) {
            throw new ZohoApiClientException("getContacts() => refresh token not set!");
        }

        $res = $this->apiClient->get('/crm/v2/contacts');

        if($res->getStatusCode() != 200) {
            throw new ZohoApiClientException("getContacts() => status code != 200");
        }

        $data = json_decode((string) $res->getBody(), true);

        $contacts = [];
        foreach($data['data'] as $contact) {
            $contacts[] = new Contact(
                $contact['id'],
                $contact['First_Name'],
                $contact['Last_Name'],
                $contact['Salutation'] ?? null,
                $contact['Mailing_Street'] ?? null,
                $contact['Mailing_Zip'] ?? null,
                $contact['Mailing_City'] ?? null,
                $contact['Mailing_Country'] ?? null,
                $contact['Email'] ?? null,
                $contact['Phone'] ?? null,
                $contact['Mobile'] ?? null,
                isset($contact['Account_Name']) ? new Account(
                    $contact['Account_Name']['id'],
                    $contact['Account_Name']['name']
                ) : null,
                $contact
            );
        }

        return $contacts;
    }

    public function createOrUpdateCustomer(Account $customer) {
        if($this->apiClient == null) {
            throw new ZohoApiClientException("createCustomer() => refresh token not set!");
        }

        // create default customer fields
        $customerFields = array();
        $customerFields["Account_Name"] = $customer->getName();
        $customerFields["Billing_Street"] = $customer->getAddress();
        $customerFields["Billing_City"] = $customer->getCity();
        $customerFields["Billing_Code"] = $customer->getZipCode();
        $customerFields["Billing_Country"] = $customer->getCountry();
        $customerFields["Phone"] = $customer->getPhone();
        $customerFields = array_merge($customerFields, $customer->getRawData());

        // merge data
        $customerData = array();
        foreach($customerFields as $key => $value) {
            $customerData[] = sprintf('"%s": "%s"', $key, $value);
        }

        // post data
        $res = $this->apiClient->post('/crm/v2/accounts/upsert', [
            'body' => '{"data": [{' . implode(",", $customerData) . '}]}'
        ]);

        if (
            $res->getStatusCode() != 200
            && $res->getStatusCode() != 201
        ) {
            throw new ZohoApiClientException("createOrUpdateCustomer() => status code != 200/201");
        }
    }

    public function createOrUpdateContact(Contact $contact) {
        if($this->apiClient == null) {
            throw new ZohoApiClientException("createOrUpdateContact() => refresh token not set!");
        }

        // create default customer fields
        $contactFields = array();
        $contactFields["First_Name"] = $contact->getFirstName();
        $contactFields["Last_Name"] = $contact->getLastName();

        if(!is_null($contact->getMailingStreet())) {
            $contactFields["Mailing_Street"] = $contact->getMailingStreet();
        }

        if(!is_null($contact->getMailingZip())) {
            $contactFields["Mailing_Zip"] = $contact->getMailingZip();
        }

        if(!is_null($contact->getMailingCity())) {
            $contactFields["Mailing_City"] = $contact->getMailingCity();
        }

        if(!is_null($contact->getMailingCountry())) {
            $contactFields["Mailing_Country"] = $contact->getMailingCountry();
        }

        if(!is_null($contact->getPhone())) {
            $contactFields["Phone"] = $contact->getPhone();
        }

        if(!is_null($contact->getMobile())) {
            $contactFields["Mobile"] = $contact->getMobile();
        }

        if(!is_null($contact->getEmail())) {
            $contactFields["Mobile"] = $contact->getEmail();
        }

        $contactFields = array_merge($contactFields, $contact->getRawData());

        // merge data
        $contactData = array();
        foreach($contactFields as $key => $value) {
            $contactData[] = sprintf('"%s": "%s"', $key, $value);
        }

        // post data
        $res = $this->apiClient->post('/crm/v2/contacts/upsert', [
            'body' => '{"data": [{' . implode(",", $contactData) . '}]}'
        ]);

        if (
            $res->getStatusCode() != 200
            && $res->getStatusCode() != 201
        ) {
            throw new ZohoApiClientException("createOrUpdateContact() => status code != 200/201");
        }
    }
}