<?php

declare(strict_types=1);

namespace App\Authors;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string id
 * @property string name
 * @property string image_url
 */
class Author extends Model
{
    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->image_url;
    }

    /**
     * @param string $image_url
     * @return $this
     */
    public function setImageUrl(string $image_url): self
    {
        $this->image_url = $image_url;

        return $this;
    }
}
