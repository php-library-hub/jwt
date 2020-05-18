<?php
/**
 * Created by PhpStorm.
 * Filename: JwtSubject.php
 * User: liumingliang
 * Email: liumingliang@qie.tv
 * Date: 2020/5/17
 * Time: 2:40 上午
 */

namespace JwtLibrary\Contracts;


interface JwtSubject
{
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJwtIdentifier();

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJwtCustomClaims();
}