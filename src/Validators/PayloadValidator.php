<?php
/**
 * Created by PhpStorm.
 * Filename: PayloadValidator.php
 * User: liumingliang
 * Email: liumingliang@qie.tv
 * Date: 2020/5/17
 * Time: 2:47 上午
 */

namespace JwtLibrary\Validators;


use JwtLibrary\Supports\ClaimCollection;
use JwtLibrary\Exceptions\JwtException;

class PayloadValidator extends Validator
{

    /**
     * The required claims.
     *
     * @var array
     */
    protected $required_claims = [
        'iss',
        'iat',
        'exp',
        'nbf',
        'sub',
        'jti',
    ];

    /**
     * Run the validations on the payload array.
     *
     *
     * @inheritDoc
     * @param ClaimCollection $value
     *
     * @return ClaimCollection
     * @throws JwtException
     */
    public function check($value)
    {
        // TODO: Implement check() method.
        $this->validateStructure($value);

        return $this->validatePayload($value);
    }

    /**
     * Ensure the payload contains the required claims and
     * the claims have the relevant type.
     *
     * @param ClaimCollection $claims
     *
     * @return void
     * @throws JwtException
     */
    protected function validateStructure(ClaimCollection $claims)
    {
        if ($this->required_claims && !$claims->hasAllClaims($this->required_claims)) {
            throw new JwtException('JWT payload does not contain the required claims');
        }
    }

    /**
     * Validate the payload timestamps.
     *
     * @param ClaimCollection $claims
     *
     * @return ClaimCollection
     */
    protected function validatePayload(ClaimCollection $claims)
    {
        return $claims->validate('payload');
    }

    /**
     * Set the required claims.
     *
     * @param array $claims
     *
     * @return $this
     */
    public function setRequiredClaims(array $claims)
    {
        $this->required_claims = $claims;

        return $this;
    }
}