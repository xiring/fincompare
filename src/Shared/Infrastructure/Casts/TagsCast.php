<?php
namespace Src\Shared\Infrastructure\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

/**
 * TagsCast class.
 *
 * @package Src\Shared\Infrastructure\Casts
 */
class TagsCast implements CastsAttributes
{
    /**
     * Handle Get.
     *
     * @param mixed $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return array
     */
    public function get($model, string $key, $value, array $attributes): array
    {
        if (is_array($value)) return $value;
        if (is_string($value) && $value !== '') {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) return $this->normalize($decoded);
            return $this->normalize(array_map('trim', explode(',', $value)));
        }
        return [];
    }

    /**
     * Handle Set.
     *
     * @param mixed $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return array
     */
    public function set($model, string $key, $value, array $attributes): array
    {
        $tags = $this->normalize(is_array($value) ? $value : (array)$value);
        return [$key => json_encode($tags)];
    }

    private function normalize(array $tags): array
    {
        $tags = array_filter(array_map(function ($t) {
            $t = is_string($t) ? trim($t) : $t;
            return $t !== '' ? (string)$t : null;
        }, $tags));
        $tags = array_values(array_unique($tags));
        return $tags;
    }
}


