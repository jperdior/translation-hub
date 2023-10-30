<?php

declare(strict_types=1);

namespace App\TranslationComponent\Infrastructure\Doctrine\Repository;

use App\TranslationComponent\Domain\Repository\TransactionRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class TransactionOrmRepository implements TransactionRepositoryInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function open()
    {
        $this->em->getConnection()->beginTransaction();
    }

    public function commit()
    {
        $this->em->getConnection()->commit();
    }

    public function rollback()
    {
        $this->em->getConnection()->rollBack();
    }
}
