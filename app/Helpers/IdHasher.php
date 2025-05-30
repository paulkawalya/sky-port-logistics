<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Crypt;

class IdHasher
{
    /**
     * Encrypt an ID
     *
     * @param int $id
     * @return string
     */
    public static function encode($id)
    {
        return Crypt::encryptString('id:' . $id);
    }

    /**
     * Decrypt an encrypted ID
     *
     * @param string $hashedId
     * @return int|null
     */
    public static function decode($hashedId)
    {
        try {
            $decrypted = Crypt::decryptString($hashedId);
            
            // Check if the decrypted string starts with 'id:'
            if (strpos($decrypted, 'id:') === 0) {
                return (int) substr($decrypted, 3);
            }
            
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
