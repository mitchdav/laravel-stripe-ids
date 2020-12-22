<?php

namespace Mitchdav\StripeIds;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class StripeIds
{
    /**
     * @var int
     */
    private $hashLength;

    /**
     * @var string
     */
    private $hashAlphabet;

    /**
     * @var array
     */
    private $prefixes;

    public function __construct(int $hashLength, string $hashAlphabet, array $prefixes = [])
    {
        $this->hashLength = $hashLength;
        $this->hashAlphabet = $hashAlphabet;
        $this->prefixes = $prefixes;
    }

    public function id(string $prefix, $hashLength = null, $hashAlphabet = null)
    {
        return $prefix.$this->hash($hashLength, $hashAlphabet);
    }

    public function hash($length = null, $alphabet = null)
    {
        $hashLength = $length ?? $this->hashLength;
        $hashAlphabet = $alphabet ?? $this->hashAlphabet;
        $hashAlphabetLength = strlen($hashAlphabet);

        return collect(str_split(random_bytes($hashLength)))
            ->map(function ($randomByte) use ($hashAlphabet, $hashAlphabetLength) {
                return $hashAlphabet[ord($randomByte) % $hashAlphabetLength];
            })
            ->join('');
    }

    public function findByStripeId($id, $prefixes = null)
    {
        /** @var string $model */
        $model = collect($prefixes ?? $this->prefixes)
            ->first(function ($model, $prefix) use ($id) {
                return Str::startsWith($id, $prefix);
            });

        if ($model !== null) {
            /** @var Model $instance */
            $instance = app($model);

            return $instance
                ->newModelQuery()
                ->whereKey($id);
        } else {
            throw new ModelNotFoundException();
        }
    }
}