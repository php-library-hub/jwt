<?php
/**
 * Created by PhpStorm.
 * Filename: Lcobucci.php
 * User: liumingliang
 * Email: liumingliang@qie.tv
 * Date: 2020/5/17
 * Time: 1:43 下午
 */

namespace JwtLibrary\Providers\Jwt;

use Exception;
use JwtLibrary\Contracts\Providers\Jwt;
use JwtLibrary\Exceptions\JwtException;
use JwtLibrary\Supports\ClaimCollection;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Ecdsa\Sha256 as ES256;
use Lcobucci\JWT\Signer\Ecdsa\Sha384 as ES384;
use Lcobucci\JWT\Signer\Ecdsa\Sha512 as ES512;
use Lcobucci\JWT\Signer\Hmac\Sha256 as HS256;
use Lcobucci\JWT\Signer\Hmac\Sha384 as HS384;
use Lcobucci\JWT\Signer\Hmac\Sha512 as HS512;
use Lcobucci\JWT\Signer\Rsa\Sha256 as RS256;
use Lcobucci\JWT\Signer\Rsa\Sha384 as RS384;
use Lcobucci\JWT\Signer\Rsa\Sha512 as RS512;

class Lcobucci extends Provider implements Jwt
{

    /**
     * The Builder instance.
     *
     * @var Builder
     */
    protected $builder;

    /**
     * The HeaderParser instance.
     *
     * @var Parser
     */
    protected $parser;

    /**
     * The signer.
     *
     * @var
     */
    private $signer;

    /**
     * Signers that this provider supports.
     *
     * @var array
     */
    protected $signers = [
        'HS256' => HS256::class,
        'HS384' => HS384::class,
        'HS512' => HS512::class,
        'RS256' => RS256::class,
        'RS384' => RS384::class,
        'RS512' => RS512::class,
        'ES256' => ES256::class,
        'ES384' => ES384::class,
        'ES512' => ES512::class,
    ];

    /**
     * Create the Lcobucci provider.
     *
     * @param Builder $builder
     * @param Parser $parser
     * @param string $secret
     * @param string $algorithm
     *
     * @return void
     * @throws JwtException
     */
    public function __construct(
        Builder $builder,
        Parser $parser,
        string $secret,
        string $algorithm
    ) {
        parent::__construct($secret, $algorithm);

        $this->builder = $builder;
        $this->parser = $parser;
        $this->signer = $this->getSigner();
    }

    /**
     * Get the signer instance.
     *
     * @return Signer
     * @throws JwtException
     */
    protected function getSigner()
    {
        if (!array_key_exists($this->algorithm, $this->signers)) {
            throw new JwtException('The given algorithm could not be found');
        }

        return new $this->signers[$this->algorithm];
    }

    /**
     * Create a JSON Web Token.
     *
     * @param array $payload
     * @return string
     */
    public function encode(array $payload)
    {
        // TODO: Implement encode() method.
        return (string)$this->builder->getToken($this->signer, $this->getSecret());
    }

    /**
     * Decode a JSON Web Token.
     *
     * @param string $token
     * @return array
     * @throws JwtException
     */
    public function decode($token)
    {
        // TODO: Implement decode() method.
        try {
            $jwt = $this->parser->parse($token);
        } catch (Exception $e) {
            throw new JwtException('Could not decode token: ' . $e->getMessage(), $e->getCode(), $e);
        }

        if (!$jwt->verify($this->signer, $this->getSecret())) {
            throw new JwtException('Token Signature could not be verified.');
        }

        return (new ClaimCollection($jwt->getClaims()))->map(function ($claim) {
            return is_object($claim) ? $claim->getValue() : $claim;
        })->toArray();
    }
}