<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240715141621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media ADD vich_file_name VARCHAR(255) DEFAULT NULL, ADD vich_file_original_name VARCHAR(255) DEFAULT NULL, ADD vich_file_mime_type VARCHAR(255) DEFAULT NULL, DROP filepath_name, DROP filepath_original_name, DROP filepath_mime_type, CHANGE filepath_size vich_file_size INT DEFAULT NULL, CHANGE filepath_dimensions vich_file_dimensions LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media ADD filepath_name VARCHAR(255) DEFAULT NULL, ADD filepath_original_name VARCHAR(255) DEFAULT NULL, ADD filepath_mime_type VARCHAR(255) DEFAULT NULL, DROP vich_file_name, DROP vich_file_original_name, DROP vich_file_mime_type, CHANGE vich_file_size filepath_size INT DEFAULT NULL, CHANGE vich_file_dimensions filepath_dimensions LONGTEXT DEFAULT NULL');
    }
}
