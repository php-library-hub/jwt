<?php
/**
 * Created by PhpStorm.
 * Filename: Validator.php
 * User: liumingliang
 * Email: liumingliang@qie.tv
 * Date: 2020/5/17
 * Time: 2:42 上午
 */

namespace JwtLibrary\Contracts;

interface Validator
{
    /**
     * Perform some checks on the value.
     *
     * @param  mixed  $value
     *
     * @return void
     */
    public function check($value);

    /**
     * Helper function to return a boolean.
     *
     * @param  array  $value
     *
     * @return bool
     */
    public function isValid($value);
}