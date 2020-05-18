<?php
/**
 * Created by PhpStorm.
 * Filename: NotBefore.php
 * User: liumingliang
 * Email: liumingliang@qie.tv
 * Date: 2020/5/17
 * Time: 1:26 上午
 */

namespace JwtLibrary\Claims\Requires;

use JwtLibrary\Claims\Claim;
use JwtLibrary\Claims\Datetime\DatetimeTrait;
use JwtLibrary\Exceptions\JwtException;

class NotBefore extends Claim
{
    use DatetimeTrait;

    /**
     * {@inheritdoc}
     */
    protected $name = 'nbf';

    /**
     * Validate the Claim within a Payload context.
     *
     * @inheritDoc
     * @return bool|void
     *
     * @throws JwtException
     */
    public function validatePayload()
    {
        if ($this->isFuture($this->getValue())) {
            throw new JwtException('Not Before (nbf) timestamp cannot be in the future');
        }
    }
}