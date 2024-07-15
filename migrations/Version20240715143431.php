<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240715143431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media ADD filepath VARCHAR(255) NOT NULL, DROP vich_file_size, DROP vich_file_dimensions, DROP vich_file_name, DROP vich_file_original_name, DROP vich_file_mime_type');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media ADD vich_file_size INT DEFAULT NULL, ADD vich_file_dimensions LONGTEXT DEFAULT NULL, ADD vich_file_name VARCHAR(255) DEFAULT NULL, ADD vich_file_original_name VARCHAR(255) DEFAULT NULL, ADD vich_file_mime_type VARCHAR(255) DEFAULT NULL, DROP filepath');
    }
}
