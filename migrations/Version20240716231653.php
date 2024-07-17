<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240716231653 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avatar (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE avatar ADD CONSTRAINT FK_1677722FBF396750 FOREIGN KEY (id) REFERENCES media (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image CHANGE file_dimensions file_dimensions LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avatar DROP FOREIGN KEY FK_1677722FBF396750');
        $this->addSql('DROP TABLE avatar');
        $this->addSql('ALTER TABLE image CHANGE file_dimensions file_dimensions LONGTEXT NOT NULL');
    }
}
