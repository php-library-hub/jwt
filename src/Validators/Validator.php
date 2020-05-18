<?php
/**
 * Created by PhpStorm.
 * Filename: Validator.php
 * User: liumingliang
 * Email: liumingliang@qie.tv
 * Date: 2020/5/17
 * Time: 2:43 上午
 */

namespace JwtLibrary\Validators;

use JwtLibrary\Contracts\Validator as ValidatorContract;
use JwtLibrary\Exceptions\JwtException;

abstract class Validator implements ValidatorContract
{
    /**
     * Helper function to return a boolean.
     *
     * @inheritDoc
     * @param  array  $value
     *
     * @return bool
     */
    public function isValid($value)
    {
        try {
            $this->check($value);
        } catch (JwtException $e) {
            return false;
        }

        return true;
    }

    /**
     * Run the validation.
     *
     * @inheritDoc
     * @param  array  $value
     *
     * @return void
     */
    abstract public function check($value);
}