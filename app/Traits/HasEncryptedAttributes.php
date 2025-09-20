<?php

namespace App\Traits;

trait HasEncryptedAttributes
{
    // A simple example: encrypted fields
    protected $encrypts = ['data']; // je fields encrypt karva che

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encrypts)) {
            $value = encrypt($value); // Laravel encrypt helper
        }

        return parent::setAttribute($key, $value);
    }

    public function decryptAttribute($value)
    {
        if (!$value) return [];
        try {
            return json_decode(decrypt($value), true);
        } catch (\Throwable $e) {
            return [];
        }
    }
}
