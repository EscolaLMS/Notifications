<?php

namespace EscolaLms\Notifications\Casts;

use EscolaLms\Notifications\Models\DatabaseNotification;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Queue\SerializesAndRestoresModelIdentifiers;

class DatabaseNotificationData implements CastsAttributes
{
    use SerializesAndRestoresModelIdentifiers;

    public function get($model, $key, $value, $attributes)
    {
        assert($model instanceof DatabaseNotification);
        assert(is_string($value));

        $data = json_decode($value, true);

        foreach ($data as $key => $attribute) {
            try {
                $unserialized = unserialize($attribute);
            } catch (\Throwable $th) {
                $unserialized = $attribute;
            }

            $data[$key] = $this->getRestoredPropertyValue($unserialized);
        }

        return $data;
    }

    public function set($model, $key, $value, $attributes)
    {
        assert($model instanceof DatabaseNotification);
        assert(is_array($value));

        $value = array_map(fn ($attribute) => is_object($attribute) ? serialize($this->getSerializedPropertyValue($attribute)) : $attribute, $value);

        return json_encode($value);
    }
}
