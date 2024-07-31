<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240730220015 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE git_hub (id INT NOT NULL, profile_picture VARCHAR(255) DEFAULT NULL, access_token VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE user ADD git_hub_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6494C8796 FOREIGN KEY (git_hub_id) REFERENCES git_hub (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6494C8796 ON user (git_hub_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE git_hub');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6494C8796');
        $this->addSql('DROP INDEX UNIQ_8D93D6494C8796 ON user');
        $this->addSql('ALTER TABLE user DROP git_hub_id');
    }
}
