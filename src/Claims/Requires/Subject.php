<?php
/**
 * Created by PhpStorm.
 * Filename: Subject.php
 * User: liumingliang
 * Email: liumingliang@qie.tv
 * Date: 2020/5/17
 * Time: 1:19 上午
 */

namespace JwtLibrary\Claims\Requires;

use JwtLibrary\Claims\Claim;

class Subject extends Claim
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'sub';
}