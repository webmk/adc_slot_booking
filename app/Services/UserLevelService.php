<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use LdapRecord\Container;

class UserLevelService
{
    protected $client;

    public function __construct()
    {
        putenv("HTTP_PROXY=");
        putenv("HTTPS_PROXY=");
        putenv("http_proxy=");
        putenv("https_proxy=");
        
        // Initialize the Guzzle HTTP client
        $this->client = new Client([
            'verify' => false,
            'timeout' => 180,
            'proxy' => null
        ]);
    }

    public function getUserLevel($cpf)
    {

        return $this->getNonProdUserLevel($cpf);
    }

    private function getNonProdUserLevel($cpf)
    {
        try {
            $response = $this->client->get(env('NON_PROD_URL') . $cpf);
            $responseBody = $response->getBody()->getContents();
            $jsonObject = json_decode($responseBody);

            return $jsonObject;
        } catch (\Exception $e) {
            Log::error('Non-Prod Error: ' . $e->getMessage());
            return 'non-prod error';
        }
    }
}