<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Http\Server\Testing;

use Swoft\Bean\Concern\PrototypeTrait;
use SwoftTest\Http\Server\Testing\Concern\HttpResponseAssertTrait;
use Swoole\Http\Response;

/**
 * Class MockResponse
 *
 * @since 2.0
 */
class MockResponse extends Response
{
    use PrototypeTrait, HttpResponseAssertTrait;

    /**
     * Status success
     */
    public const STATUS_SUCCESS = 200;

    /**
     * @var string
     */
    private $content = '';

    /**
     * @var int
     */
    private $status = 0;

    /**
     * @var string
     */
    private $downFile = '';

    /**
     * @return MockResponse
     */
    public static function new(): self
    {
        // return self::__instance();
        return new self;
    }

    /**
     * @param mixed $content
     *
     * @return void
     */
    public function end($content = null)
    {
        $this->content = $content;
    }

    /**
     * @param $html
     */
    public function write($html)
    {
    }

    /**
     * @param      $key
     * @param      $value
     * @param null $ucwords
     */
    public function header($key, $value, $ucwords = null)
    {
        $this->header[$key] = $value;
    }

    /**
     * @param string          $name
     * @param string|null     $value
     * @param int|string|null $expires
     * @param string|null     $path
     * @param string|null     $domain
     * @param bool|null       $secure
     * @param bool|null       $httpOnly
     * @param string|null     $samesite
     * @param string|null     $priority
     */
    public function cookie(
        $name,
        $value = null,
        $expires = null,
        $path = null,
        $domain = null,
        $secure = null,
        $httpOnly = null,
        $samesite = null,
        $priority = null
    ) {
        $result = \urlencode($name) . '=' . \urlencode($value);

        if ($domain) {
            $result .= '; domain=' . $domain;
        }

        if ($path) {
            $result .= '; path=' . $path;
        }

        if ($expires) {
            if (\is_string($expires)) {
                $timestamp = \strtotime($expires);
            } else {
                $timestamp = (int)$expires;
            }

            if ($timestamp !== 0) {
                $result .= '; Expires=' . \gmdate('D, d-M-Y H:i:s e', $timestamp);
            }
        }

        if ($secure) {
            $result .= '; Secure';
        }

        if ($httpOnly) {
            $result .= '; HttpOnly';
        }

        if ($samesite) {
            $result .= '; SameSite=' . $samesite;
        }

        if ($priority) {
            $result .= '; Priority=' . $priority;
        }

        $this->cookie[$name] = $result;
    }

    /**
     * 设置HttpCode，如404, 501, 200
     *
     * @param int    $code
     * @param string $reason
     */
    public function status($code, $reason = null)
    {
        $this->status = $code;
    }

    /**
     * 设置Http压缩格式
     *
     * @param int $level
     */
    public function gzip($level = 1)
    {
    }

    /**
     * 发送静态文件
     *
     * @param      $filename
     * @param null $offset
     * @param null $length
     *
     * @internal param string $level
     */
    public function sendfile($filename, $offset = null, $length = null)
    {
        $this->downFile = $filename;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getFd()
    {
        return $this->fd;
    }

    /**
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getHeader(string $key, $default = null)
    {
        return $this->header[$key] ?? $default;
    }

    /**
     * @return array|mixed
     */
    public function getHeaders()
    {
        return $this->header;
    }

    /**
     * @return mixed
     */
    public function getCookie()
    {
        return $this->cookie;
    }

    /**
     * @return mixed
     */
    public function getTrailer()
    {
        return $this->trailer;
    }

    /**
     * @param string $key
     * @param null   $default
     *
     * @return mixed
     */
    public function getHeaderKey(string $key, $default = null)
    {
        return $this->header[$key] ?? $default;
    }

    /**
     * @return string
     */
    public function getDownFile(): string
    {
        return $this->downFile;
    }
}
