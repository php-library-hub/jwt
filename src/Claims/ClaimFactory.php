<?php
/**
 * Created by PhpStorm.
 * Filename: ClaimFactory.php
 * User: liumingliang
 * Email: liumingliang@qie.tv
 * Date: 2020/5/18
 * Time: 2:27 下午
 */

namespace JwtLibrary\Claims;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use JwtLibrary\Supports\Utils;
use JwtLibrary\Claims\Requires\Audience;
use JwtLibrary\Claims\Requires\Expiration;
use JwtLibrary\Claims\Requires\IssuedAt;
use JwtLibrary\Claims\Requires\Issuer;
use JwtLibrary\Claims\Requires\JwtId;
use JwtLibrary\Claims\Requires\NotBefore;
use JwtLibrary\Claims\Requires\Subject;

class ClaimFactory
{
    /**
     * The request.
     *
     * @var Request
     */
    protected $request;

    /**
     * The TTL.
     *
     * @var int
     */
    protected $ttl = 60;

    /**
     * Time leeway in seconds.
     *
     * @var int
     */
    protected $leeway = 0;

    /**
     * The classes map.
     *
     * @var array
     */
    private $class_map = [
        'aud' => Audience::class,
        'exp' => Expiration::class,
        'iat' => IssuedAt::class,
        'iss' => Issuer::class,
        'jti' => JwtId::class,
        'nbf' => NotBefore::class,
        'sub' => Subject::class,
    ];

    /**
     * Constructor.
     *
     * @param Request $request
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get the instance of the claim when passing the name and value.
     *
     * @param  string  $name
     * @param  mixed  $value
     *
     * @return Claim
     */
    public function get($name, $value)
    {
        if ($this->has($name)) {
            $claim = new $this->class_map[$name]($value);

            return method_exists($claim, 'setLeeway') ?
                $claim->setLeeway($this->leeway) :
                $claim;
        }

        return new CustomClaim($name, $value);
    }

    /**
     * Check whether the claim exists.
     *
     * @param  string  $name
     *
     * @return bool
     */
    public function has($name)
    {
        return array_key_exists($name, $this->class_map);
    }

    /**
     * Generate the initial value and return the Claim instance.
     *
     * @param  string  $name
     *
     * @return Claim
     */
    public function make($name)
    {
        return $this->get($name, $this->$name());
    }

    /**
     * Get the Issuer (iss) claim.
     *
     * @return string
     */
    public function iss()
    {
        return $this->request->url();
    }

    /**
     * Get the Issued At (iat) claim.
     *
     * @return int
     */
    public function iat()
    {
        return Utils::now()->getTimestamp();
    }

    /**
     * Get the Expiration (exp) claim.
     *
     * @return int
     */
    public function exp()
    {
        return Utils::now()->addMinutes($this->ttl)->getTimestamp();
    }

    /**
     * Get the Not Before (nbf) claim.
     *
     * @return int
     */
    public function nbf()
    {
        return Utils::now()->getTimestamp();
    }

    /**
     * Get the JWT Id (jti) claim.
     *
     * @return string
     */
    public function jti()
    {
        return Str::random();
    }

    /**
     * Add a new claim mapping.
     *
     * @param  string  $name
     * @param  string  $classPath
     *
     * @return $this
     */
    public function extend($name, $classPath)
    {
        $this->class_map[$name] = $classPath;

        return $this;
    }

    /**
     * Set the request instance.
     *
     * @param  Request  $request
     *
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Set the token ttl (in minutes).
     *
     * @param  int  $ttl
     *
     * @return $this
     */
    public function setTTL($ttl)
    {
        $this->ttl = $ttl;

        return $this;
    }

    /**
     * Get the token ttl.
     *
     * @return int
     */
    public function getTTL()
    {
        return $this->ttl;
    }

    /**
     * Set the leeway in seconds.
     *
     * @param  int  $leeway
     *
     * @return $this
     */
    public function setLeeway($leeway)
    {
        $this->leeway = $leeway;

        return $this;
    }
}