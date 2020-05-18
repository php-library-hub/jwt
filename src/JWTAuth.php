<?php
/**
 * Created by PhpStorm.
 * Filename: JWTAuth.php
 * User: liumingliang
 * Email: liumingliang@qie.tv
 * Date: 2020/5/18
 * Time: 4:09 下午
 */

namespace JwtLibrary;


use JwtLibrary\Contracts\JwtSubject;
use JwtLibrary\Exceptions\JwtException;
use JwtLibrary\Supports\CustomClaims;

class JWTAuth
{
    use CustomClaims;

    /**
     * The authentication manager.
     *
     * @var Manager
     */
    protected $manager;

    /**
     * The HTTP parser.
     *
     * @var HeaderParser
     */
    protected $parser;

    /**
     * The token.
     *
     * @var Token|null
     */
    protected $token;

    /**
     * Lock the subject.
     *
     * @var bool
     */
    protected $lock_subject = true;

    public function __construct(Manager $manager, HeaderParser $parser)
    {
        $this->manager = $manager;
        $this->parser = $parser;
    }

    /**
     * Generate a token for a given subject.
     *
     * @param JwtSubject $subject
     *
     * @return string
     * @throws Exceptions\JwtException
     */
    public function fromSubject(JWTSubject $subject)
    {
        $payload = $this->makePayload($subject);

        return $this->manager->encode($payload)->get();
    }

    /**
     * Alias to generate a token for a given user.
     *
     * @param JwtSubject $user
     *
     * @return string
     * @throws Exceptions\JwtException
     */
    public function fromUser(JWTSubject $user)
    {
        return $this->fromSubject($user);
    }

    /**
     * Create a Payload instance.
     *
     * @param JwtSubject $subject
     *
     * @return TokenPayload
     * @throws Exceptions\JwtException
     */
    public function makePayload(JWTSubject $subject)
    {
        return $this->factory()->customClaims($this->getClaimsArray($subject))->make();
    }

    /**
     * Get the Payload Factory.
     *
     * @return Factory
     */
    public function factory()
    {
        return $this->manager->getPayloadFactory();
    }

    /**
     * Convenience method to get a claim value.
     *
     * @param string $claim
     *
     * @return mixed
     */
    public function getClaim($claim)
    {
        return $this->payload()->get($claim);
    }

    public function getClaimsArray(JwtSubject $subject)
    {
        return array_merge(
            $this->getClaimsForSubject($subject),
            $subject->getJWTCustomClaims(), // custom claims from JWTSubject method
            $this->custom_claims // custom claims from inline setter
        );
    }

    /**
     * Get the claims associated with a given subject.
     *
     * @param JWTSubject $subject
     *
     * @return array
     */
    private function getClaimsForSubject(JwtSubject $subject)
    {
        return array_merge([
            'sub' => $subject->getJWTIdentifier(),
        ], $this->lock_subject ? ['prv' => $this->hashSubjectModel($subject)] : []);
    }

    /**
     * Hash the subject model and return it.
     *
     * @param string|object $model
     *
     * @return string
     */
    protected function hashSubjectModel($model)
    {
        return sha1(is_object($model) ? get_class($model) : $model);
    }

    /**
     * Check if the subject model matches the one saved in the token.
     *
     * @param string|object $model
     *
     * @return bool
     */
    public function checkSubjectModel($model)
    {
        if (($prv = $this->payload()->get('prv')) === null) {
            return true;
        }

        return $this->hashSubjectModel($model) === $prv;
    }


    /**
     * Ensure that a token is available.
     *
     * @return void
     * @throws JwtException
     *
     */
    protected function requireToken()
    {
        if (!$this->token) {
            throw new JwtException('A token is required');
        }
    }

    /**
     * Invalidate a token (add it to the blacklist).
     *
     * @param bool $forceForever
     *
     * @return $this
     * @throws JwtException
     */
    public function invalidate($forceForever = false)
    {
        $this->requireToken();

        $this->manager->invalidate($this->token, $forceForever);

        return $this;
    }

    /**
     * Alias for getPayload().
     *
     * @return TokenPayload
     */
    public function payload()
    {
        try {
            return $this->getPayload();
        } catch (JwtException $e) {
        }
    }

    /**
     * Get the raw Payload instance.
     *
     * @return TokenPayload
     * @throws JwtException
     */
    public function getPayload()
    {
        $this->requireToken();

        return $this->manager->decode($this->token);
    }
}