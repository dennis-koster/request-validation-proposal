<?php

declare(strict_types=1);

namespace App\Http;

use Illuminate\Foundation\Http\FormRequest;
use Lcobucci\JWT\Token;
use App\Auth\AccessTokenRepository;
use App\Auth\AuthorizationService;
use App\Auth\Contracts\AccessTokenInterface;
use App\Auth\Contracts\TokenAuthenticatorInterface;
use MyParcelCom\JsonApi\Exceptions\InvalidAccessTokenException;
use Symfony\Component\Translation\Exception\InvalidResourceException;

abstract class AbstractApiRequest extends FormRequest
{
    /**
     * @var AuthorizationService
     */
    protected $authorizationService;

    /**
     * @var AccessTokenRepository
     */
    protected $accessTokenRepository;

    /**
     * @var JsonRequestValidator
     */
    protected $jsonRequestValidator;

    /**
     * @var TokenAuthenticatorInterface
     */
    private $tokenAuthenticator;

    /**
     * @param AuthorizationService        $authorizationService
     * @param AccessTokenRepository       $accessTokenRepository
     * @param TokenAuthenticatorInterface $tokenAuthenticator
     * @param JsonRequestValidator        $jsonRequestValidator
     * @param array                       $query
     * @param array                       $request
     * @param array                       $attributes
     * @param array                       $cookies
     * @param array                       $files
     * @param array                       $server
     * @param null                        $content
     */
    public function __construct(
        AuthorizationService $authorizationService,
        AccessTokenRepository $accessTokenRepository,
        TokenAuthenticatorInterface $tokenAuthenticator,
        JsonRequestValidator $jsonRequestValidator,
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null
    ) {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);

        $this->authorizationService = $authorizationService;
        $this->accessTokenRepository = $accessTokenRepository;
        $this->tokenAuthenticator = $tokenAuthenticator;
        $this->jsonRequestValidator = $jsonRequestValidator;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        try {
            $this
                ->authenticateToken()
                ->validatePermissions();

            return true;
        } catch (\Exception $exception) {
            // Add a sensible error message to the response body
        }

        return false;
    }

    /**
     *
     */
    public function validate()
    {
        $this
            ->validateSchema()
            ->validateResource();
    }

    /**
     * @throws \MyParcelCom\JsonApi\Exceptions\InvalidJsonSchemaException
     * @throws \MyParcelCom\JsonApi\Exceptions\ResourceConflictException
     */
    protected function validateSchema()
    {
        $this->jsonRequestValidator
            ->setRequest($this)
            ->validate($this->getPath());
    }

    /**
     * Check whether or not the token in the headers
     * is valid.
     *
     * @return $this
     * @throws InvalidAccessTokenException
     */
    protected function authenticateToken(): self
    {
        $authHeader = $this->header('Authorization');
        if ($authHeader === null || strpos($authHeader, 'Bearer ') !== 0) {
            throw new InvalidAccessTokenException('No or invalid Authorization header supplied');
        }

        $tokenString = str_ireplace('Bearer ', '', $authHeader);
        $user = $this->tokenAuthenticator->authenticate($tokenString);
        /** @var Token $token */
        $token = $user->getToken();

        /** @var AccessTokenInterface $accessToken */
        $accessToken = $this->accessTokenRepository->getById($token->getHeader('jti'));
        if ($accessToken === null || $accessToken->isRevoked()) {
            throw new InvalidAccessTokenException('The provided token has been revoked.');
        }
    }

    /**
     * Return the path of the JSON schema that belongs
     * to the request.
     *
     * @return string
     */
    abstract protected function getPath(): string;

    /**
     * Check whether or not current user has all the
     * right scopes to perform the request.
     *
     * @return $this
     */
    abstract protected function validatePermissions(): self;

    /**
     * Check whether the combination of resource attributes
     * given is valid. For instance, does the selected
     * region match the selected service contract.
     *
     * @throws InvalidResourceException
     * @return $this
     */
    abstract protected function validateResource(): self;

    /**
     * The data to be passed to a repository instance. In the case
     * of a patch request, it can return the id of the resource
     * so it does not have to be fetched again.
     *
     * @return array
     */
    abstract protected function getMappedData(): array;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [];
    }
}
