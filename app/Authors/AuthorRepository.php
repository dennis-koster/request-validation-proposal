<?php

declare(strict_types=1);

namespace App\Authors;

class AuthorRepository
{
    /**
     * @param string $id
     * @return Author
     */
    public function findOrFail(string $id): Author
    {
        return Author::findOrFail($id);
    }

    /**
     * @param array $data
     * @return Author
     */
    public function create(array $data): Author
    {
        $author = new Author();

        $this->setProperties($data, $author);

        $author->save();

        return $author;
    }

    /**
     * @param array $data
     * @param Author  $author
     * @return Author
     */
    public function update(array $data, Author $author)
    {
        $this->setProperties($data, $author);

        $author->save();

        return $author;
    }

    /**
     * @param array $properties
     * @param Author  $author
     * @return Author
     */
    public function setProperties(array $properties, Author $author): Author
    {
        foreach ($properties as $property => $value) {
            $this->setProperty($property, $value, $author);
        }

        return $author;
    }

    /**
     * @param string $key
     * @param        $value
     * @param Author   $author
     * @return Author
     */
    private function setProperty(string $key, $value, Author $author): Author
    {
        switch ($key) {
            case 'name':
                return $author->setName($value);

            case 'image_url':
                return $author->setImageUrl($value);
        }
    }

}
