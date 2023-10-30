<?php

declare(strict_types=1);

namespace App\TranslationComponent\Domain\Repository;

interface TransactionRepositoryInterface
{
    public function open();

    public function commit();

    public function rollback();
}
