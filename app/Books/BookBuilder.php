<?php

declare(strict_types=1);

namespace App\Books;

use Book;

class BookBuilder
{
    /**
     * @param array $data
     * @return Book
     */
    public function build(array $data): Book
    {
        return (new Book())
            ->setAuthor($data['author']);
    }

}
