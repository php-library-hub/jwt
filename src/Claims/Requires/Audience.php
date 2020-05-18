<?php
/**
 * Created by PhpStorm.
 * Filename: Audience.php
 * User: liumingliang
 * Email: liumingliang@qie.tv
 * Date: 2020/5/17
 * Time: 12:44 上午
 */

namespace JwtLibrary\Claims\Requires;

use JwtLibrary\Claims\Claim;

class Audience extends Claim
{
    /**
     * @inheritDoc
     */
    protected $name = 'aud';
}