<?php

namespace App\Contracts;

interface ConnectorInterface
{

    /**
     * Implement authentication logic to connect to the third party
     * API
     */
    public function authenticate();

    /**
     * Set the authenticate header to support Guzzle client
     *
     * @return array
     */
    public function authHeaders(): array;

    /**
     * Send the request to the remote endpoint
     *
     * @param string API endpoint to send the request to
     * @param array parameters. If present, send POST reques, otherwise - GET
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function doRequest(string $endpoint, array $params);

    /**
     * Pull data out of the response from the endpoint
     *
     * @param string the data to decode from JSON
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getData($data);

    /**
     * Generate the response property map
     *
     * e.g return ['FirstName' => $data['first_name'],...];
     * e.g return $data;
     *
     * @param $data
     * @return array
     */
    public function parse($data): array;

    /**
     * Set a unique key for this data collection for caching purposes
     *
     * @return string
     */
    public function getJobKey(): string;

    /**
     * Expose additional api methods if you are using a library
     * supplied by the vendor
     */
    public function getApi();
}
