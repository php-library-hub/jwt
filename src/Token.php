<?php
/**
 * Created by PhpStorm.
 * Filename: Token.php
 * User: liumingliang
 * Email: liumingliang@qie.tv
 * Date: 2020/5/17
 * Time: 2:30 下午
 */

namespace JwtLibrary;

use JwtLibrary\Validators\TokenValidator;

class Token
{
    /**
     * @var string
     */
    private $value;

    /**
     * Create a new JSON Web Token.
     *
     * @param string $value
     *
     * @return void
     * @throws Exceptions\JwtException
     */
    public function __construct($value)
    {
        $this->value = (string) (new TokenValidator)->check((array)$value);
    }

    /**
     * Get the token.
     *
     * @return string
     */
    public function get()
    {
        return $this->value;
    }

    /**
     * Get the token when casting to string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->get();
    }
}