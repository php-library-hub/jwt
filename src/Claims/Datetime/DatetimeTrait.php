<?php
/**
 * Created by PhpStorm.
 * Filename: DatetimeTrait.php
 * User: liumingliang
 * Email: liumingliang@qie.tv
 * Date: 2020/5/17
 * Time: 1:21 上午
 */

namespace JwtLibrary\Claims\Datetime;

use DateInterval;
use DateTimeInterface;
use JwtLibrary\Exceptions\JwtException;
use JwtLibrary\Supports\Utils;

trait DatetimeTrait
{
    /**
     * Time leeway in seconds.
     *
     * @var int
     */
    protected $leeway = 0;

    /**
     * Set the claim value, and call a validate method.
     *
     * @inheritDoc
     * @param  mixed  $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        if ($value instanceof DateInterval) {
            $value = Utils::now()->add($value);
        }

        if ($value instanceof DateTimeInterface) {
            $value = $value->getTimestamp();
        }

        return parent::setValue($value);
    }

    /**
     * Validate the claim in a standalone Claim context.
     *
     * @param $value
     *
     * @return int|string
     * @throws JwtException
     */
    public function validateCreate($value)
    {
        if (! is_numeric($value)) {
            throw new JwtException($this);
        }

        return $value;
    }

    /**
     * Determine whether the value is in the future.
     *
     * @param  mixed  $value
     *
     * @return bool
     */
    protected function isFuture($value)
    {
        return Utils::isFuture($value, $this->leeway);
    }

    /**
     * Determine whether the value is in the past.
     *
     * @param  mixed  $value
     *
     * @return bool
     */
    protected function isPast($value)
    {
        return Utils::isPast($value, $this->leeway);
    }

    /**
     * Set the leeway in seconds.
     *
     * @param  int  $leeway
     *
     * @return $this
     */
    public function setLeeway($leeway)
    {
        $this->leeway = $leeway;

        return $this;
    }
}