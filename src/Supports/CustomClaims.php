<?php
/**
 * Created by PhpStorm.
 * Filename: CustomClaims.php
 * User: liumingliang
 * Email: liumingliang@qie.tv
 * Date: 2020/5/17
 * Time: 12:23 上午
 */

namespace JwtLibrary\Supports;

trait CustomClaims
{
    /**
     * Custom claims.
     *
     * @var array
     */
    protected $custom_claims = [];

    /**
     * Set the custom claims.
     *
     * @param array $custom_claims
     *
     * @return $this
     */
    public function customClaims(array $custom_claims): self
    {
        $this->custom_claims = $custom_claims;

        return $this;
    }

    /**
     * Alias to set the custom claims.
     *
     * @param array $custom_claims
     *
     * @return $this
     */
    public function claims(array $custom_claims): self
    {
        return $this->customClaims($custom_claims);
    }

    /**
     * Get the custom claims.
     *
     * @return array
     */
    public function getCustomClaims(): array
    {
        return $this->custom_claims;
    }
}