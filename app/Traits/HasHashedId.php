<?php

namespace App\Traits;

use Hashids\Hashids;

trait HasHashedId
{
    public function getHashedIdAttribute()
    {
        $hashids = app(Hashids::class);
        return $hashids->encode($this->id);
    }

    public static function findByHash($hash)
    {
        $hashids = app(Hashids::class);
        $id = $hashids->decode($hash)[0] ?? null;
        return $id ? static::find($id) : null;
    }
}
