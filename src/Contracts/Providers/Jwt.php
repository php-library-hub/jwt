<?php
/**
 * Created by PhpStorm.
 * Filename: Jwt.php
 * User: liumingliang
 * Email: liumingliang@qie.tv
 * Date: 2020/5/17
 * Time: 1:39 下午
 */

namespace JwtLibrary\Contracts\Providers;


interface Jwt
{
    /**
     * @param array $payload
     *
     * @return string
     */
    public function encode(array $payload);

    /**
     * @param string $token
     *
     * @return array
     */
    public function decode($token);
}