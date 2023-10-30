<?php

declare(strict_types=1);

namespace App\TranslationComponent\Infrastructure\HttpClient;

use InvalidArgumentException;

class ClientHelper
{
    public function prepareBody(array $body, string $bodyFormat): string
    {
        return match ($bodyFormat) {
            'json' => json_encode($body),
            'x-www-form-urlencoded' => $this->bodyToFormUrlEncoded($body),
            default => throw new InvalidArgumentException('Invalid body format'),
        };
    }

    private function bodyToFormUrlEncoded(?array $params): string
    {
        $params = $params ?? [];
        $fields = [];
        foreach ($params as $key => $value) {
            $name = \urlencode($key);
            if (is_array($value)) {
                $fields[] = implode(
                    '&',
                    array_map(
                        function (string $textElement) use ($name): string {
                            return $name . '=' . \urlencode($textElement);
                        },
                        $value
                    )
                );
            } elseif (is_null($value)) {
                // Parameters with null value are skipped
            } else {
                $fields[] = $name . '=' . \urlencode($value);
            }
        }

        return implode("&", $fields);
    }
}
