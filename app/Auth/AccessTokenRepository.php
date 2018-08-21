<?php declare(strict_types=1);

namespace App\Auth;

use App\Auth\Contracts\AccessTokenInterface;
use App\Auth\Contracts\AccessTokenRepositoryInterface;

class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    /**
     * @param string $id
     * @return AccessTokenInterface|null
     */
    public function getById(string $id): ?AccessTokenInterface
    {
        return AccessToken::query()
            ->where('id', '=', $id)
            ->first();
    }
}
