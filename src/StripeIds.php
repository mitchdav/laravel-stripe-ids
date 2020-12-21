<?php

namespace Mitchdav\StripeIds;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class StripeIds
{
    /**
     * @var string
     */
    private $alphabet;

    /**
     * @var int
     */
    private $alphabetLength;

    /**
     * @var int
     */
    private $length;

    /**
     * @var string
     */
    private $separator;

    /**
     * @var array
     */
    private $prefixes;

    public function __construct(string $alphabet, int $length, string $separator, array $prefixes = [])
    {
        $this->alphabet = $alphabet;
        $this->alphabetLength = strlen($alphabet);
        $this->length = $length;
        $this->separator = $separator;
        $this->prefixes = $prefixes;
    }

    public function id(string $prefix)
    {
        return $prefix.$this->separator.$this->hash();
    }

    public function hash()
    {
        return collect(str_split(random_bytes($this->length)))
            ->map(function ($randomByte) {
                return $this->alphabet[ord($randomByte) % $this->alphabetLength];
            })
            ->join('');
    }

    public function findByStripeId($id)
    {
        /** @var string $model */
        $model = collect($this->prefixes)
            ->first(function ($model, $prefix) use ($id) {
                /** @var HasStripeId $instance */
                $instance = app($model);

                return Str::startsWith($id, $prefix.$instance->getStripeIdSeparator());
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