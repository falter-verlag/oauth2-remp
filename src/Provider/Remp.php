<?php

namespace Falter\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class Remp extends AbstractProvider
{
    use BearerAuthorizationTrait;

    /**
     * URL to the REMP instance, e.g. https://crm.press
     *
     * @var string
     */
    public $rempUrl = 'http://localhost:8080';

    /**
     * @inheritDoc
     */
    public function __construct(array $options = [], array $collaborators = [])
    {
        if (isset($options['rempUrl'])) {
            $this->rempUrl = $options['rempUrl'];
        }

        parent::__construct($options, $collaborators);
    }

    /**
     * @inheritDoc
     */
    public function getBaseAuthorizationUrl()
    {
        return $this->rempUrl . '/authorize';
    }

    /**
     * @inheritDoc
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->rempUrl . '/oauth/access_token';
    }

    /**
     * @inheritDoc
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return $this->rempUrl . '/api/v1/user/info?source=oauth_token';
    }

    /**
     * @inheritDoc
     */
    protected function getDefaultScopes()
    {
        return ['default'];
    }

    /**
     * @inheritDoc
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if ($response->getStatusCode() >= 400) {
            $error = $data['error'] ?? 'Unknown error';
            $code = $data['code'] ?? $response->getStatusCode();

            throw new IdentityProviderException($error, $code, $data);
        }
    }

    /**
     * @inheritDoc
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new RempUser($response);
    }
}
