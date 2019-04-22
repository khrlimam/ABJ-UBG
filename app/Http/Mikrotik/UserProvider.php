<?php

namespace App\Http\Mikrotik;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider as IUserProvider;
use KhairulImam\ROSWrapper\Wrapper as Mikrotik;

class UserProvider implements IUserProvider
{

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param mixed $identifier
     * @return Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        return session($identifier, null);
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param mixed $identifier
     * @param string $token
     * @return Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param Authenticatable $user
     * @param string $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param array $credentials
     * @return Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        return new User($credentials);
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param Authenticatable $user
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $host = $credentials['host-ip'];
        $username = $credentials['username'];
        $password = $credentials['password'];
        $mikrotik = new Mikrotik($host, $username, $password);
        if ($mikrotik->connected) {
            session([$username => $user]);
        }
        return $mikrotik->connected;
    }

}