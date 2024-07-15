<?php

namespace App\Service\Doctrine;

use Doctrine\Common\DataFixtures\Purger\PurgerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurgerInterface;

class TruncatePurger implements PurgerInterface, ORMPurgerInterface
{
    private EntityManagerInterface $em;

    /* PurgerInterface */
    public function purge()
    {
        $conn = $this->em->getConnection();
        $databaseName = $conn->getDatabase();
        $tableNames = $conn->createSchemaManager()->listTableNames();

        foreach ($tableNames as $tableName) {
            $fullTableName = '`' . $databaseName . '`.`' . $tableName . '`';
            $query = '
				START TRANSACTION;
				SAVEPOINT ' . $fullTableName . ';
				SET FOREIGN_KEY_CHECKS = 0;
				TRUNCATE TABLE ' . $fullTableName . ';
				SET FOREIGN_KEY_CHECKS = 1;
				COMMIT;
			';
            $conn->executeQuery($query);
        }
    }

    /* ORMPurgerInterface */
    public function setEntityManager(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
}
