<?php
/**
 * Created by PhpStorm.
 * Filename: Provider.php
 * User: liumingliang
 * Email: liumingliang@qie.tv
 * Date: 2020/5/17
 * Time: 1:40 下午
 */

namespace JwtLibrary\Providers\Jwt;

use Lcobucci\JWT\Signer\Key;

abstract class Provider
{
    /**
     * The secret.
     *
     * @var string
     */
    protected $secret;

    /**
     * The used algorithm.
     *
     * @var string
     */
    protected $algorithm;

    /**
     * Constructor.
     *
     * @param string $secret
     * @param string $algorithm
     *
     * @return void
     */
    public function __construct(string $secret, string $algorithm)
    {
        $this->secret = new Key($secret);
        $this->algorithm = $algorithm;
    }

    /**
     * Set the algorithm used to sign the token.
     *
     * @param string $algorithm
     *
     * @return $this
     */
    public function setAlgorithm($algorithm)
    {
        $this->algorithm = $algorithm;

        return $this;
    }

    /**
     * Get the algorithm used to sign the token.
     *
     * @return string
     */
    public function getAlgorithm()
    {
        return $this->algorithm;
    }

    /**
     * Set the secret used to sign the token.
     *
     * @param string $secret
     *
     * @return $this
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * Get the secret used to sign the token.
     *
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

}