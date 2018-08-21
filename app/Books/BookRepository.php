<?php

declare(strict_types=1);

namespace App\Books;

class BookRepository
{
    /**
     * @param string $id
     * @return Book
     */
    public function findOrFail(string $id): Book
    {
        return Book::findOrFail($id);
    }

    /**
     * @param array $data
     * @return Book
     */
    public function create(array $data): Book
    {
        $book = new Book();

        $this->setProperties($data, $book);

        $book->save();

        return $book;
    }


    /**
     * @param array $data
     * @param Book  $book
     * @return Book
     */
    public function update(array $data, Book $book): Book
    {
        $this->setProperties($data, $book);

        $book->save();

        return $book;
    }

    /**
     * @param array $properties
     * @param Book  $book
     * @return Book
     */
    public function setProperties(array $properties, Book $book): Book
    {
        foreach ($properties as $property => $value) {
            $this->setProperty($property, $value, $book);
        }

        return $book;
    }

    /**
     * @param string $key
     * @param        $value
     * @param Book   $book
     * @return Book
     */
    private function setProperty(string $key, $value, Book $book): Book
    {
        switch ($key) {
            case 'name':
                return $book->setName($value);

            case 'author':
                return $book->setAuthorId($value);

            case 'year':
                return $book->setYear($value);

            case 'description':
                return $book->setDescription($value);
        }
    }

}
