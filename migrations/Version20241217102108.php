<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241217102108 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE foundry_owner (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE foundry ADD owner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE foundry ADD CONSTRAINT FK_1D6D45B17E3C61F9 FOREIGN KEY (owner_id) REFERENCES foundry_owner (id)');
        $this->addSql('CREATE INDEX IDX_1D6D45B17E3C61F9 ON foundry (owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE foundry DROP FOREIGN KEY FK_1D6D45B17E3C61F9');
        $this->addSql('DROP TABLE foundry_owner');
        $this->addSql('DROP INDEX IDX_1D6D45B17E3C61F9 ON foundry');
        $this->addSql('ALTER TABLE foundry DROP owner_id');
    }
}
