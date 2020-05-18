<?php
/**
 * Created by PhpStorm.
 * Filename: IssuedAt.php
 * User: liumingliang
 * Email: liumingliang@qie.tv
 * Date: 2020/5/17
 * Time: 1:24 上午
 */

namespace JwtLibrary\Claims\Requires;

use JwtLibrary\Claims\Claim;
use JwtLibrary\Claims\Datetime\DatetimeTrait;
use JwtLibrary\Exceptions\JwtException;

class IssuedAt extends Claim
{
    use DatetimeTrait {
        validateCreate as commonValidateCreate;
    }

    /**
     * {@inheritdoc}
     */
    protected $name = 'iat';

    /**
     * Validate the claim in a standalone Claim context.
     *
     * @inheritDoc
     * @param mixed $value
     *
     * @return bool|mixed
     * @throws JwtException
     */
    public function validateCreate($value)
    {
        $this->commonValidateCreate($value);

        if ($this->isFuture($value)) {
            throw new JwtException($this);
        }

        return $value;
    }

    /**
     * Validate the Claim within a Payload context.
     *
     * @inheritDoc
     *
     * @return bool|void
     * @throws JwtException
     */
    public function validatePayload()
    {
        if ($this->isFuture($this->getValue())) {
            throw new JwtException('Issued At (iat) timestamp cannot be in the future');
        }
    }

    /**
     * Validate the Claim within a refresh context.
     *
     * @inheritDoc
     * @param int $refreshTtl
     * @return bool|void
     *
     * @throws JwtException
     */
    public function validateRefresh(int $refreshTtl)
    {
        if ($this->isPast($this->getValue() + $refreshTtl * 60)) {
            throw new JwtException('Token has expired and can no longer be refreshed');
        }
    }
}