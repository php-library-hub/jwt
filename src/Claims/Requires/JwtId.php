<?php
/**
 * Created by PhpStorm.
 * Filename: JwtId.php
 * User: liumingliang
 * Email: liumingliang@qie.tv
 * Date: 2020/5/17
 * Time: 1:26 上午
 */

namespace JwtLibrary\Claims\Requires;

use JwtLibrary\Claims\Claim;

class JwtId extends Claim
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'jti';
}