<?php namespace App\Auth;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Support\Str;

class GoogleApiUserProvider implements UserProvider {

    protected $model;

    public function __construct(UserContract $model)
    {
        $this->model = $model;
    }

    public function retrieveById($identifier)
    {
        $query = \App\User::whereStatus(1);

        return $query
            ->where('id', '=', $identifier)
            ->first();
    }

    public function retrieveByToken($identifier, $token)
    {
        if (!$user = \App\User::getByToken($credentials['api_token'],$identifier))
        {
            if ($token_info = \App\Tools\Tools::GoogleTokenCheck($token))
            {
                $email = $token_info['email'];
                if ($user = \App\User::getByEmail($email, $identifier)) 
                {
                    $user->addToken($token)->save();
                }
            }
        }
        return $user;

    }

    public function updateRememberToken(UserContract $user, $token)
    {

    }

    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials)) {
            return;
        }

        // First we will add each credential element to the query as a where clause.
        // Then we can execute the query and, if we found a user, return it in a
        // Eloquent User "model" that will be utilized by the Guard instances.
        if (isset($credentials['api_token'])) {
            if (!$user = \App\User::getByToken($credentials['api_token']))
            {
                if ($token_info = \App\Tools\Tools::GoogleTokenCheck($credentials['api_token']))
                {
                    $email = $token_info['email'];
                    if ($user = \App\User::getByEmail($email)) 
                    {
                        $user->addToken($credentials['api_token']);
                    }
                }
            }
            return $user;
        }
     
        $query = \App\User::whereStatus(1);
        foreach ($credentials as $key => $value) {
            if (! Str::contains($key, 'password')) {
                $query->where($key, $value);
            }
        }
        return $query->first();
    }

    public function validateCredentials(UserContract $user, array $credentials)
    {

    }

}