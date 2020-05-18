<?php
/**
 * Created by PhpStorm.
 * Filename: TokenPayload.php
 * User: liumingliang
 * Email: liumingliang@qie.tv
 * Date: 2020/5/18
 * Time: 2:55 下午
 */

namespace JwtLibrary;


use ArrayAccess;
use BadMethodCallException;
use Countable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Arr;
use JsonSerializable;
use JwtLibrary\Claims\Claim;
use JwtLibrary\Exceptions\JwtException;
use JwtLibrary\Supports\ClaimCollection;
use JwtLibrary\Validators\PayloadValidator;
use phpDocumentor\Reflection\Types\Mixed_;

class TokenPayload implements
    ArrayAccess,
    Arrayable,
    Countable,
    Jsonable,
    JsonSerializable
{

    /**
     * The collection of claims.
     *
     * @var ClaimCollection
     */
    private $claims;

    /**
     * TokenPayload constructor.
     *
     * @param ClaimCollection $claims
     * @param PayloadValidator $validator
     * @throws Exceptions\JwtException
     */
    public function __construct(
        ClaimCollection $claims,
        PayloadValidator $validator
    ) {
        $this->claims = $validator->check($claims);
    }

    /**
     * Get the array of claim instances.
     *
     * @return ClaimCollection
     */
    public function getClaims(): ClaimCollection
    {
        return $this->claims;
    }

    /**
     * Checks if a payload matches some expected values.
     *
     * @param array $values
     * @param bool $strict
     *
     * @return bool
     */
    public function matches(array $values, bool $strict = false): bool
    {
        if (empty($values)) {
            return false;
        }

        $claims = $this->getClaims();

        foreach ($values as $key => $value) {
            if (!$claims->has($key) || !$claims->get($key)->matches($value, $strict)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks if a payload strictly matches some expected values.
     *
     * @param array $values
     *
     * @return bool
     */
    public function matchesStrict(array $values): bool
    {
        return $this->matches($values, true);
    }

    /**
     * Get the payload.
     *
     * @param mixed $claim
     *
     * @return mixed
     */
    public function get($claim = null)
    {
        $claim = value($claim);

        if ($claim !== null) {
            if (is_array($claim)) {
                return array_map([$this, 'get'], $claim);
            }

            return Arr::get($this->toArray(), $claim);
        }

        return $this->toArray();
    }

    /**
     * Get the underlying Claim instance.
     *
     * @param string $claim
     *
     * @return Claims\Claim
     */
    public function getInternal(string $claim): Claims\Claim
    {
        return $this->claims->getByClaimName($claim);
    }

    /**
     * Determine whether the payload has the claim (by instance).
     *
     * @param Claim $claim
     *
     * @return bool
     */
    public function has(Claim $claim)
    {
        return $this->claims->has($claim->getName());
    }

    /**
     * Determine whether the payload has the claim (by key).
     *
     * @param string $claim
     *
     * @return bool
     */
    public function hasKey(string $claim)
    {
        return $this->offsetExists($claim);
    }

    /**
     * Get the array of claims.
     *
     * @return array
     */
    public function toArray()
    {
        // TODO: Implement toArray() method.
        return $this->claims->toPlainArray();
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
        return $this->toArray();
    }

    /**
     * Get the payload as JSON.
     *
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
     * Determine if an item exists at an offset.
     *
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
        return Arr::has($this->toArray(), $offset);
    }

    /**
     * Get an item at a given offset.
     *
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        // TODO: Implement offsetGet() method.
        return Arr::get($this->toArray(), $offset);
    }

    /**
     * Don't allow changing the payload as it should be immutable.
     *
     * @param mixed $offset
     * @param mixed $value
     *
     * @return void
     * @throws JwtException
     */
    public function offsetSet($offset, $value): void
    {
        // TODO: Implement offsetSet() method.
        throw new JwtException('The payload is immutable');
    }

    /**
     * Don't allow changing the payload as it should be immutable.
     *
     * @param string $offset
     *
     * @return void
     * @throws JwtException
     *
     */
    public function offsetUnset($offset): void
    {
        // TODO: Implement offsetUnset() method.
        throw new JwtException('The payload is immutable');
    }

    /**
     * Count the number of claims.
     *
     * @return int
     */
    public function count(): int
    {
        // TODO: Implement count() method.
        return count($this->toArray());
    }

    /**
     * Get the payload as a string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }

    /**
     * Invoke the Payload as a callable function.
     *
     * @param mixed $claim
     *
     * @return mixed
     */
    public function __invoke($claim = null)
    {
        return $this->get($claim);
    }

    /**
     * Magically get a claim value.
     *
     * @param string $method
     * @param array $parameters
     *
     * @return mixed
     * @throws BadMethodCallException
     *
     */
    public function __call(string $method, array $parameters)
    {
        if (preg_match('/get(.+)\b/i', $method, $matches)) {
            foreach ($this->claims as $claim) {
                if (get_class($claim) === 'JwtLibrary\\Claims\\' . $matches[1]) {
                    return $claim->getValue();
                }
            }
        }

        throw new BadMethodCallException(
            sprintf('The claim [%s] does not exist on the payload.', $method)
        );
    }
}