<?php
/**
 * Created by PhpStorm.
 * Filename: Manager.php
 * User: liumingliang
 * Email: liumingliang@qie.tv
 * Date: 2020/5/18
 * Time: 3:57 下午
 */

namespace JwtLibrary;


use JwtLibrary\Contracts\Providers\Jwt;
use JwtLibrary\Supports\CustomClaims;

class Manager
{
    use CustomClaims;

    /**
     * The provider.
     *
     * @var Jwt
     */
    protected $provider;

    /**
     * the payload factory.
     *
     * @var Factory
     */
    protected $payload_factory;

    /**
     * Constructor.
     *
     * @param Jwt $provider
     * @param Factory $payload_factory
     */
    public function __construct(Jwt $provider, Factory $payload_factory)
    {
        $this->provider = $provider;
        $this->payload_factory = $payload_factory;
    }

    /**
     * Encode a Payload and return the Token.
     *
     * @param TokenPayload $payload
     *
     * @return Token
     * @throws Exceptions\JwtException
     */
    public function encode(TokenPayload $payload): Token
    {
        $token = $this->provider->encode($payload->get());

        return new Token($token);
    }

    /**
     * Decode a Token and return the Payload.
     *
     * @param Token $token
     * @return TokenPayload
     * @throws Exceptions\JwtException
     */
    public function decode(Token $token): TokenPayload
    {
        $payload_array = $this->provider->decode($token->get());

        return $this->payload_factory
            ->customClaims($payload_array)
            ->make();
    }


    /**
     * Get the Payload Factory instance.
     *
     * @return Factory
     */
    public function getPayloadFactory(): Factory
    {
        return $this->payload_factory;
    }

    /**
     * Get the JWTProvider instance.
     *
     * @return Jwt
     */
    public function getJWTProvider(): Jwt
    {
        return $this->provider;
    }


}