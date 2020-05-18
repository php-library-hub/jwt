<?php
/**
 * Created by PhpStorm.
 * Filename: CustomClaim.php
 * User: liumingliang
 * Email: liumingliang@qie.tv
 * Date: 2020/5/18
 * Time: 2:28 下午
 */

namespace JwtLibrary\Claims;


class CustomClaim extends Claim
{
    /**
     * @param string $name
     * @param mixed $value
     *
     * @return void
     */
    public function __construct($name, $value)
    {
        parent::__construct($value);
        $this->setName($name);
    }
}