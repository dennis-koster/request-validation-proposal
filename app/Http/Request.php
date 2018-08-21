<?php

declare(strict_types=1);

namespace App\Http;

use Illuminate\Http\Request as BaseRequest;
use MyParcelCom\JsonApi\Http\Interfaces\RequestInterface;
use MyParcelCom\JsonApi\Http\Traits\RequestTrait;

class Request extends BaseRequest implements RequestInterface
{
    use RequestTrait;

    public const HEADER_ACCEPT_JSON = 'application/vnd.api+json';
    public const HEADER_ACCEPT_PDF = 'application/pdf';
    public const HEADER_ACCEPT_PNG = 'image/png';
    public const HEADER_ACCEPT_JPEG = 'image/jpeg';
    public const HEADER_ACCEPT_ALL = '*/*';

    /**
     * @inheritdoc
     */
    public function header($key = null, $default = null)
    {
        $headerValue = parent::header($key, $default);

        // When the accept header contains accept all(*/*) we handle it as not being set at all
        if (strtolower($key) === 'accept' && strpos($headerValue, self::HEADER_ACCEPT_ALL) !== false) {
            return $default;
        }

        return $headerValue;
    }
}
