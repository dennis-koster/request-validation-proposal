<?php

declare(strict_types=1);

namespace App\Books;

use Book;

class BookRepository
{
    /**
     * @param array $data
     * @param Book  $book
     */
    public function update(array $data, Book $book)
    {
        foreach ($data as $key => $value) {
            $this->updateProperty($key, $value, $book);
        }

    }

    /**
     * @param string $key
     * @param        $value
     * @param Book   $book
     * @return string
     */
    private function updateProperty(string $key, $value, Book $book): string
    {
        switch ($key) {
            case 'name':
                return $book->setName($value);
        }
    }

}
