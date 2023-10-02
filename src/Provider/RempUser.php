<?php

namespace Falter\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Tool\ArrayAccessorTrait;

class RempUser implements ResourceOwnerInterface
{
    use ArrayAccessorTrait;

    /**
     * Raw response of the API request.
     *
     * @var array
     */
    protected $response;

    /**
     * Creates new resource owner.
     *
     * @param array $response
     */
    public function __construct(array $response = array())
    {
        $this->response = $response;
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->getValueByKey($this->response, 'user.id');
    }

    /**
     * Get the user's first name.
     *
     * @return string|null
     */
    public function getFirstName()
    {
        return $this->getValueByKey($this->response, 'user.first_name');
    }

    /**
     * Get the user's last name.
     *
     * @return string|null
     */
    public function getLastName()
    {
        return $this->getValueByKey($this->response, 'user.last_name');
    }

    /**
     * Get the user's email address.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->getValueByKey($this->response, 'user.email');
    }

    /**
     * Get the user's metadata.
     *
     * @return array
     */
    public function getMetadata()
    {
        return $this->getValueByKey($this->response, 'user_meta');
    }

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return $this->response;
    }
}
