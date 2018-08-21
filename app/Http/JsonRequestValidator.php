<?php

declare(strict_types=1);

namespace App\Http;

use JsonSchema\Exception\InvalidSchemaException;
use JsonSchema\Validator;
use MyParcelCom\JsonApi\Exceptions\InvalidJsonSchemaException;
use MyParcelCom\JsonApi\Exceptions\ResourceConflictException;
use stdClass;

class JsonRequestValidator
{
    /** @var stdClass */
    protected $schema;

    /** @var Validator */
    protected $validator;

    /** @var Request */
    protected $request;

    /**
     * Validates currently set Request with schema for given path.
     *
     * @param string      $schemaPath
     * @param string|null $method
     * @param string|null $accept
     * @throws InvalidJsonSchemaException
     * @throws ResourceConflictException
     */
    public function validate(string $schemaPath, string $method = null, string $accept = null): void
    {
        $method = $method ?? strtolower($this->request->getMethod());
        $accept = $accept ?? strtolower($this->request->header('Accept', Request::HEADER_ACCEPT_JSON));

        $schema = $this->getSchema($schemaPath, $method, $accept);

        $postData = json_decode($this->request->getContent());
        $this->validator->validate($postData, $schema);

        if ($this->validator->getErrors()) {
            if ($this->validator->getErrors()[0]['property'] === 'data.type') {
                throw new ResourceConflictException('type');
            } else {
                throw new InvalidJsonSchemaException($this->validator->getErrors());
            }
        }
    }

    /**
     * Get the schema for given path, method and accept header. Checks the spec
     * version to determine where to find the schema.
     *
     * @param string $schemaPath
     * @param string $method
     * @param string $accept
     * @return stdClass
     */
    protected function getSchema(string $schemaPath, string $method, string $accept): stdClass
    {
        if (isset($this->schema->openapi) && (int)$this->schema->openapi === 3) {
            return $this->schema->paths->{$schemaPath}->{strtolower($method)}->requestBody->content->{$accept}->schema;
        }

        if (isset($this->schema->swagger) && (int)$this->schema->swagger === 2) {
            $schemaParams = $this->schema->paths->{$schemaPath}->{$method}->parameters;
            foreach ($schemaParams as $schemaParam) {
                if ($schemaParam->in === 'body') {
                    return $schemaParam->schema;
                }
            }

            throw new InvalidSchemaException(
                sprintf(
                    'Could not find schema for path "%s" with method "%s" and accept header "%s"',
                    $schemaPath,
                    $method,
                    $accept
                )
            );
        }

        throw new InvalidSchemaException(
            'Used schema is of unknown version, expected "swagger v2" or "openapi v3"'
        );
    }

    /**
     * @param stdClass $schema
     * @return $this
     */
    public function setSchema(stdClass $schema): self
    {
        $this->schema = $schema;

        return $this;
    }

    /**
     * @param Validator $validator
     * @return $this
     */
    public function setValidator(Validator $validator): self
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function setRequest(Request $request): self
    {
        $this->request = $request;

        return $this;
    }
}
