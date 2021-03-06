<?php

namespace Remp\NetteSso\Security;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use Nette\Http\IRequest;
use Nette\Utils\Json;

class Client
{
    const ENDPOINT_INTROSPECT = 'api/auth/introspect';

    const ENDPOINT_REFRESH = 'api/auth/refresh';

    private $request;

    private $client;

    public function __construct($ssoAddr, IRequest $request)
    {
        $this->client = new GuzzleClient([
            'base_uri' => $ssoAddr,
        ]);
        $this->request = $request;
    }

    /**
     * introspect attempts to obtain user data based on the provided SSO $token.
     *
     * @param $token
     * @return array
     * @throws SsoExpiredException
     * @throws \Nette\Security\AuthenticationException
     */
    public function introspect($token)
    {
        try {
            $response = $this->client->request('GET', self::ENDPOINT_INTROSPECT, [
                'headers' => [
                    'Authorization' => 'Bearer '.$token,
                ]
            ]);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $contents = $response->getBody()->getContents();
            $body = Json::decode($contents);
            switch ($response->getStatusCode()) {
                case 400:
                case 401:
                    $e = new SsoExpiredException();
                    $e->redirect = $body->redirect;
                    throw $e;
                default:
                    throw new \Nette\Security\AuthenticationException($contents);
            }
        }

        $user = Json::decode($response->getBody()->getContents(), Json::FORCE_ARRAY);
        return $user;
    }

    public function refresh($token)
    {
        try {
            $response = $this->client->request('POST', self::ENDPOINT_REFRESH, [
                'headers' => [
                    'Authorization' => 'Bearer '.$token,
                ]
            ]);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $contents = $response->getBody()->getContents();
            $body = Json::decode($contents);
            switch ($response->getStatusCode()) {
                case 400:
                case 401:
                    $e = new SsoExpiredException();
                    $e->redirect = $body->redirect;
                    throw $e;
                default:
                    throw new \Nette\Security\AuthenticationException($contents);
            }
        }

        $tokenResponse = Json::decode($response->getBody()->getContents(), Json::FORCE_ARRAY);
        return $tokenResponse;
    }
}