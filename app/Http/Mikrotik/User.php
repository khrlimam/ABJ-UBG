<?php

namespace App\Http\Mikrotik;

use Illuminate\Contracts\Auth\Authenticatable;
use KhairulImam\ROSWrapper\Wrapper as Mikrotik;

class User implements Authenticatable
{

    private $credentials;

    function __construct(array $credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * @return array users credentials that used to login
     */
    public function getCredentials()
    {
        return $this->credentials;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return "";
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param string $value
     * @return void
     */
    public function setRememberToken($value)
    {
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return "no remember2ing";
    }

    public function mikrotik()
    {
        return new Mikrotik(
            $this->getHostIp(),
            $this->getAuthIdentifier(),
            $this->getAuthPassword());
    }

    private function getHostIp()
    {
        return $this->credentials['host-ip'];
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->credentials[$this->getAuthIdentifierName()];
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'username';
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->credentials['password'];
    }

}