<?php

namespace APN\YourConnector;

use App\Contracts\ConnectorInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class YourConnector implements ConnectorInterface {

    const API_PREFIX = 'api';

    protected $_key;
    protected $_baseUrl;
    protected $_client;
    protected $_authenticated;

    public function __construct($key, $domain)
    {
        $this->_key = $key;
        $this->_baseUrl = 'https://' . $domain . '.freshsales.io/' . self::API_PREFIX;
        $this->_client = new Client([
            'headers' => $this->authHeaders()
        ]);
    }

    public function authenticate()
    {
        $request = new Request('GET', $this->_baseUrl . '/selector/owners');

        try {
            $response = json_decode($this->_client->send($request)->getBody()->getContents());
            if(!empty($response->users)) {
                $this->_authenticated = true;
            } else {
                return false;
            }
            return true;
        } catch(Exception $e) {
            return false;
        }
    }

    public function authHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Authorization' => 'Token token=' . $this->_key
        ];
    }

    public function doRequest(string $endpoint, array $params = null)
    {
        if($this->_authenticated) {
            $method = empty($params) ? 'GET' : 'POST';

            $request = new Request($method, $this->_baseUrl . $endpoint);
            $response = $this->_client->send($request)->getBody()->getContents();

            return $this->getData($response);
        }
    }

    public function getApi()
    {
        // TODO: Implement getApi() method.
    }

    public function getData($data)
    {
        return json_decode($data);
    }

    public function getJobKey(): string
    {
        // TODO: Implement getJobKey() method.
    }

    public function parse($data): array
    {
        $results = [];
        foreach ($data as $item) {
            $results[] = (object)[
                'first_name' => $item->first_name,
                'last_name'  => $item->last_name,
                'company'    => $item->company->name,
                'address'    => $item->address,
                'city'       => $item->city,
                'state'      => $item->state,
                'zipcode'        => $item->zipcode
            ];
        }

        return $results;
    }
}
