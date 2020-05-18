<?php
/**
 * Created by PhpStorm.
 * Filename: Expiration.php
 * User: liumingliang
 * Email: liumingliang@qie.tv
 * Date: 2020/5/17
 * Time: 1:23 上午
 */

namespace JwtLibrary\Claims\Requires;

use JwtLibrary\Claims\Claim;
use JwtLibrary\Claims\Datetime\DatetimeTrait;
use JwtLibrary\Exceptions\JwtException;

class Expiration extends Claim
{
    use DatetimeTrait;

    /**
     * @inheritdoc}
     */
    protected $name = 'exp';

    /**
     * Validate the Claim within a Payload context.
     *
     * @inheritdoc
     *
     * @return bool|void
     * @throws JwtException
     */
    public function validatePayload()
    {
        if ($this->isPast($this->getValue())) {
            throw new JwtException('Token has expired');
        }
    }
}