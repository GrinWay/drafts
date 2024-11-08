<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241102075342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_order (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(50) NOT NULL, user_id BINARY(16) DEFAULT NULL, UNIQUE INDEX UNIQ_17EB68C0A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE user_order ADD CONSTRAINT FK_17EB68C0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product ADD user_order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD6D128938 FOREIGN KEY (user_order_id) REFERENCES user_order (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD6D128938 ON product (user_order_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_order DROP FOREIGN KEY FK_17EB68C0A76ED395');
        $this->addSql('DROP TABLE user_order');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD6D128938');
        $this->addSql('DROP INDEX IDX_D34A04AD6D128938 ON product');
        $this->addSql('ALTER TABLE product DROP user_order_id');
    }
}
