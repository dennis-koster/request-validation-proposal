<?php

namespace App\Books;

use Symfony\Component\Translation\Exception\InvalidResourceException;

class PatchBookRequest extends AbstractApiRequest
{
    /**
     * Check whether or not current user has all the
     * right scopes to perform the request.
     *
     * @return $this
     */
    protected function validatePermissions(): AbstractApiRequest
    {
        // TODO: Implement validatePermissions() method.
    }

    /**
     * Return the path of the JSON schema that belongs
     * to the request.
     *
     * @return string
     */
    protected function getPath(): string
    {
        return '/books';
    }

    /**
     * The data to be passed to a builder instance. In the case
     * of a patch request, it can return the id of the resource
     * so it does not have to be fetched again.
     *
     * @return array
     */
    public function getBuilderData(): array
    {
        return [
            'book_id' => $this->get('id'),
            'author' => $this->get('author'),
            // etc
        ];
    }

    /**
     * Check whether the combination of resource attributes
     * given is valid. For instance, does the selected
     * region match the selected service contract.
     *
     * @throws InvalidResourceException
     * @return $this
     */
    protected function validateResource(): AbstractApiRequest
    {
        // TODO: Implement validateResource() method.
    }
}
