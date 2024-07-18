<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240717150348 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image ADD file_dimensions LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE media ADD filepath VARCHAR(255) NOT NULL, ADD file_original_name VARCHAR(255) NOT NULL, DROP filepath_name, DROP filepath_original_name, DROP filepath_mime_type, DROP filepath_size, DROP filepath_dimensions');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP file_dimensions');
        $this->addSql('ALTER TABLE media ADD filepath_name VARCHAR(255) DEFAULT NULL, ADD filepath_original_name VARCHAR(255) DEFAULT NULL, ADD filepath_mime_type VARCHAR(255) DEFAULT NULL, ADD filepath_size INT DEFAULT NULL, ADD filepath_dimensions LONGTEXT DEFAULT NULL, DROP filepath, DROP file_original_name');
    }
}
