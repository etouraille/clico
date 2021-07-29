<?php


namespace App\Model;


class Jwt
{
    private $token;
    private $expire;

    public function __construct(string $token, int $expire) {
        $this->token = $token;
        $this->expire = $expire;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token): void
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getExpire()
    {
        return $this->expire;
    }

    /**
     * @param mixed $expire
     */
    public function setExpire($expire): void
    {
        $this->expire = $expire;
    }


}
