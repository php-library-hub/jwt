<?php
/**
 * Created by PhpStorm.
 * Filename: Factory.php
 * User: liumingliang
 * Email: liumingliang@qie.tv
 * Date: 2020/5/18
 * Time: 2:41 下午
 */

namespace JwtLibrary;

use JwtLibrary\Claims\ClaimFactory;
use JwtLibrary\Claims\Claim;
use JwtLibrary\Supports\ClaimCollection;
use JwtLibrary\Supports\CustomClaims;
use JwtLibrary\Validators\PayloadValidator;

class Factory
{
    use CustomClaims;

    /**
     * The claim factory.
     *
     * @var claimFactory
     */
    protected $claim_factory;

    /**
     * The validator.
     *
     * @var PayloadValidator
     */
    protected $validator;

    /**
     * The default claims.
     *
     * @var array
     */
    protected $default_claims = [
        'iss',
        'iat',
        'exp',
        'nbf',
        'jti',
    ];

    /**
     * The claims collection.
     *
     * @var CustomClaims
     */
    protected $claims;


    /**
     * Constructor.
     *
     * @param ClaimFactory $claim_factory
     * @param PayloadValidator $validator
     *
     */
    public function __construct(ClaimFactory $claim_factory, PayloadValidator $validator)
    {
        $this->claim_factory = $claim_factory;
        $this->validator = $validator;
        $this->claims = new ClaimCollection();
    }

    /**
     * Empty the claims collection.
     *
     * @return $this
     */
    public function emptyClaims(): self
    {
        $this->claims = new ClaimCollection;

        return $this;
    }

    /**
     * Create the Payload instance.
     *
     * @param bool $reset_claims
     *
     * @return TokenPayload
     * @throws Exceptions\JwtException
     */
    public function make(bool $reset_claims = false): TokenPayload
    {
        if ($reset_claims) {
            $this->emptyClaims();
        }

        return $this->withClaims($this->buildClaimsCollection());
    }

    /**
     * Get a Payload instance with a claims collection.
     *
     * @param ClaimCollection $claims
     *
     * @return TokenPayload
     * @throws Exceptions\JwtException
     */
    public function withClaims(ClaimCollection $claims): TokenPayload
    {
        return new TokenPayload($claims, $this->validator);
    }

    /**
     * Build and get the Claims Collection.
     *
     * @return ClaimCollection
     */
    public function buildClaimsCollection(): ClaimCollection
    {
        return $this->buildClaims()->resolveClaims();
    }

    /**
     * Build the default claims.
     *
     * @return $this
     */
    protected function buildClaims(): self
    {
        // remove the exp claim if it exists and the ttl is null
        if ($this->claim_factory->getTtl() === null && $key = array_search('exp', $this->default_claims)) {
            unset($this->default_claims[$key]);
        }

        // add the default claims
        foreach ($this->default_claims as $claim) {
            $this->addClaim($claim, $this->claim_factory->make($claim));
        }

        // add custom claims on top, allowing them to overwrite defaults
        return $this->addCustomClaim($this->getCustomClaims());
    }

    /**
     * Build out the Claim DTO's.
     *
     * @return ClaimCollection
     */
    protected function resolveClaims(): ClaimCollection
    {
        return $this->claims->map(function ($value, $name) {
            return $value instanceof Claim ? $value : $this->claim_factory->get($name, $value);
        });
    }

    /**
     * Add a claim to the Payload.
     *
     * @param string $name
     * @param mixed $value
     *
     * @return $this
     */
    protected function addClaim(string $name, $value): self
    {
        $this->claims->put($name, $value);

        return $this;
    }

    /**
     * Add an array of claims to the Payload.
     *
     * @param array $claims
     *
     * @return $this
     */
    protected function addCustomClaim(array $claims): self
    {
        foreach ($claims as $name => $value) {
            $this->addClaim($name, $value);
        }

        return $this;
    }

    /**
     * Helper to set the ttl.
     *
     * @param int $ttl
     *
     * @return $this
     */
    public function setTTL(int $ttl): self
    {
        $this->claim_factory->setTTL($ttl);

        return $this;
    }

    /**
     * Helper to get the ttl.
     *
     * @return int
     */
    public function getTTL(): int
    {
        return $this->claim_factory->getTTL();
    }

    /**
     * Get the default claims.
     *
     * @return array
     */
    public function getDefaultClaims(): array
    {
        return $this->default_claims;
    }

    /**
     * Get the PayloadValidator instance.
     *
     * @return PayloadValidator
     */
    public function validator(): PayloadValidator
    {
        return $this->validator;
    }

    /**
     * Magically add a claim.
     *
     * @param string $method
     * @param array $parameters
     *
     * @return $this
     */
    public function __call($method, $parameters)
    {
        $this->addClaim($method, $parameters[0]);

        return $this;
    }
}