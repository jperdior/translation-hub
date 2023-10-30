<?php

declare(strict_types=1);

namespace App\TranslationComponent\Application\Exception;

use Exception;

class QueueTranslationException extends Exception
{
    public function __construct($message)
    {
        parent::__construct(message: $message);
    }
}
