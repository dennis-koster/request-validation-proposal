<?php

declare(strict_types=1);

namespace App\Books;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /**
     * @param int $authorId
     * @return $this
     */
    public function setAuthor(int $authorId)
    {
        $this->author_id = $authorId;

        return $this;
    }

}
