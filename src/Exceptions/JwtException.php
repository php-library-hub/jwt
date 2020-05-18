<?php
/**
 * Created by PhpStorm.
 * Filename: JwtException.php
 * User: liumingliang
 * Email: liumingliang@qie.tv
 * Date: 2020/5/17
 * Time: 1:29 上午
 */

namespace JwtLibrary\Exceptions;

use Exception;

class JwtException extends Exception
{
    /**
     * {@inheritdoc}
     */
    protected $message = 'An error occurred';
}