<?php
/**
 * Created by PhpStorm.
 * Filename: Claim.php
 * User: liumingliang
 * Email: liumingliang@qie.tv
 * Date: 2020/5/17
 * Time: 12:50 上午
 */

namespace JwtLibrary\Claims;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;
use JwtLibrary\Contracts\Claim as ClaimContract;

abstract class Claim implements Arrayable, Jsonable, JsonSerializable, ClaimContract
{

    /**
     * The claim name.
     *
     * @var string
     */
    protected $name;

    /**
     * The claim value.
     *
     * @var mixed
     */
    private $value;

    /**
     * @param mixed $value
     *
     * @return void
     */
    public function __construct($value)
    {
        $this->setValue($value);
    }

    /**
     * Set the claim value, and call a validate method.
     *
     * @inheritDoc
     * @param mixed $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        // TODO: Implement setValue() method.
        $this->value = $this->validateCreate($value);
        return $this;
    }

    /**
     * Get the claim value.
     *
     * @inheritDoc
     *
     * @return mixed
     */
    public function getValue()
    {
        // TODO: Implement getValue() method.
        return $this->value;
    }

    /**
     * Set the claim name.
     *
     * @inheritDoc
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        // TODO: Implement setName() method.
        $this->name = $name;
        return $this;
    }

    /**
     * Get the claim name.
     *
     * @inheritDoc
     *
     * @return string
     */
    public function getName()
    {
        // TODO: Implement getName() method.
        return $this->name;
    }

    /**
     * Checks if the value matches the claim.
     *
     * @param mixed $value
     * @param bool $strict
     *
     * @return bool
     */
    public function matches($value, $strict = true)
    {
        return $strict ? $this->value === $value : $this->value == $value;
    }

    /**
     * Build a key value array comprising of the claim name and value.
     *
     * @inheritDoc
     *
     * @return array
     */
    public function toArray()
    {
        // TODO: Implement toArray() method.
        return [$this->getName() => $this->getValue()];
    }

    /**
     * Get the claim as JSON.
     *
     * @inheritDoc
     * @param int $options
     *
     * @return string
     */
    public function toJson($options = JSON_UNESCAPED_SLASHES)
    {
        // TODO: Implement toJson() method.
        return json_encode($this->toArray(), $options);
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @inheritDoc
     *
     * @return array
     */
    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
        return $this->toArray();
    }

    /**
     * Validate the claim in a standalone Claim context.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function validateCreate($value)
    {
        // TODO: Implement validateCreate() method.
        return $value;
    }

    /**
     * Validate the Claim within a Payload context.
     *
     * @return bool
     */
    public function validatePayload()
    {
        return $this->getValue();
    }

    /**
     * Validate the Claim within a refresh context.
     *
     * @param int $refreshTtl
     *
     * @return bool
     */
    public function validateRefresh(int $refreshTtl)
    {
        return $this->getValue();
    }

    /**
     * Get the payload as a string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }
}