<?php
/**
 * Created by PhpStorm.
 * Filename: HeaderParser.php
 * User: liumingliang
 * Email: liumingliang@qie.tv
 * Date: 2020/5/17
 * Time: 2:32 上午
 */

namespace JwtLibrary;

use Illuminate\Http\Request;

class HeaderParser
{

    /**
     * The request.
     *
     * @var Request
     */
    protected $request;

    /**
     * The header name.
     *
     * @var string
     */
    protected $header = 'authorization';

    /**
     * The header prefix.
     *
     * @var string
     */
    protected $prefix = 'bearer';

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
     * Iterate through the parsers and attempt to retrieve
     * a value, otherwise return null.
     *
     * @return string|null
     */
    public function parseToken(): string
    {
        return $response = $this->parse($this->request);
    }

    /**
     * Check whether a token exists in the chain.
     *
     * @return bool
     */
    public function hasToken(): bool
    {
        return $this->parseToken() !== null;
    }

    /**
     * Attempt to parse the token from some other possible headers.
     *
     * @param Request $request
     *
     * @return null|string
     */
    protected function fromAltHeaders(Request $request): string
    {
        return $request->server->get('HTTP_AUTHORIZATION')
            ?: $request->server->get('REDIRECT_HTTP_AUTHORIZATION');
    }

    /**
     * Try to parse the token from the request header.
     *
     * @param Request $request
     *
     * @return null|string
     */
    public function parse(Request $request): string
    {
        $header = $request->headers->get($this->header) ?: $this->fromAltHeaders($request);

        if ($header && preg_match('/' . $this->prefix . '\s*(\S+)\b/i', $header, $matches)) {
            return $matches[1];
        }
    }

    /**
     * Set the request instance.
     *
     * @param Request $request
     *
     * @return $this
     */
    public function setRequest(Request $request): self
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Set the header name.
     *
     * @param string $headerName
     *
     * @return $this
     */
    public function setHeaderName(string $headerName): self
    {
        $this->header = $headerName;

        return $this;
    }

    /**
     * Set the header prefix.
     *
     * @param string $headerPrefix
     *
     * @return $this
     */
    public function setHeaderPrefix(string $headerPrefix): self
    {
        $this->prefix = $headerPrefix;

        return $this;
    }
}