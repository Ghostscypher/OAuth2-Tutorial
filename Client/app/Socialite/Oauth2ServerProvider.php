<?php

namespace App\Socialite;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;
use Illuminate\Support\Arr;

class Oauth2ServerProvider extends AbstractProvider implements ProviderInterface
{
    protected $base_uri = 'http://oauth2-server.test';

    /**
     * @return
     */
    public function setBaseURI($base_uri)
    {
        $this->base_uri = $base_uri;

        return $this;
    }

    /**
     * Returns 
     */
    public function getBaseURI()
    {
        return $this->base_uri;
    }

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase("{$this->base_uri}/oauth/authorize", $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return "{$this->base_uri}/oauth/token";
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()
            ->get(
            "{$this->base_uri}/api/user",
            $this->getRequestOptions($token)
        );

        return json_decode($response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessTokenResponse($code){
        $response = $this->getHttpClient()
            ->post($this->getTokenUrl(), [

            'headers' => ['Accept' => 'application/json'],

            'form_params' => $this->getTokenFields($code),

        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id' => $user['id'],
            'name' => Arr::get($user, 'name'),
            'email' => Arr::get($user, 'email'),
        ]);
    }

    /**
     * Get the default options for an HTTP request.
     *
     * @param string $token
     * @return array
     */
    protected function getRequestOptions($token)
    {
        return [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => "Bearer $token",
            ],
        ];
    }
}
