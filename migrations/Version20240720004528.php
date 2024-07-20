<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240720004528 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_8d93d649e7927c74 TO UNIQ_USER_EMAIL');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_8d93d649abf410d0 TO UNIQ_USER_PASSPORT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_user_passport TO UNIQ_8D93D649ABF410D0');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_user_email TO UNIQ_8D93D649E7927C74');
    }
}
