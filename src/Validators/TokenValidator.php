<?php
/**
 * Created by PhpStorm.
 * Filename: TokenValidator.php
 * User: liumingliang
 * Email: liumingliang@qie.tv
 * Date: 2020/5/17
 * Time: 2:45 上午
 */

namespace JwtLibrary\Validators;

use JwtLibrary\Exceptions\JwtException;

class TokenValidator extends Validator
{

    /**
     * Check the structure of the token.
     * @param array $value
     * @inheritDoc
     *
     * @throws JwtException
     */

    public function check($value)
    {
        // TODO: Implement check() method.
        return $this->validateStructure($value);
    }


    /**
     * @param $token
     *
     * @return mixed
     * @throws JwtException
     */
    protected function validateStructure($token)
    {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            throw new JwtException('Wrong number of segments');
        }

        $parts = array_filter(array_map('trim', $parts));

        if (count($parts) !== 3 || implode('.', $parts) !== $token) {
            throw new JwtException('Malformed token');
        }

        return $token;
    }
}