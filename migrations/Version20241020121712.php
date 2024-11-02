<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241020121712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app_easy_admin_cinema (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, duration INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE app_easy_admin_comment (id INT AUTO_INCREMENT NOT NULL, author_name VARCHAR(255) NOT NULL, text LONGTEXT NOT NULL, type VARCHAR(255) NOT NULL, invitation_interval VARCHAR(255) DEFAULT NULL, number_from_zero_to_hundred DOUBLE PRECISION NOT NULL, updated_at DATETIME(6) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE avatar (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE food_product (expires_at DATETIME(6) NOT NULL, id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE furniture_product (color VARCHAR(255) NOT NULL, id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE git_hub (id INT NOT NULL, profile_picture VARCHAR(255) DEFAULT NULL, access_token VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE image (file_dimensions LONGTEXT DEFAULT NULL, id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE machine (id INT AUTO_INCREMENT NOT NULL, year INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE media (file_token VARCHAR(60) DEFAULT NULL, key_frames LONGTEXT DEFAULT NULL, file_version INT NOT NULL, id INT AUTO_INCREMENT NOT NULL, filepath VARCHAR(255) NOT NULL, file_original_name VARCHAR(255) NOT NULL, created_at DATETIME(6) NOT NULL, updated_at DATETIME(6) DEFAULT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, price VARCHAR(255) NOT NULL, is_public TINYINT(1) DEFAULT 0 NOT NULL, updated_at DATETIME(6) DEFAULT NULL, created_at DATETIME(6) NOT NULL, passport_id INT DEFAULT NULL, user_id BINARY(16) DEFAULT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D34A04ADABF410D0 (passport_id), INDEX IDX_D34A04ADA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE product_passport (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, category JSON NOT NULL, updated_at DATETIME(6) DEFAULT NULL, created_at DATETIME(6) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE reset_password_request (id BINARY(16) NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME(6) NOT NULL, expires_at DATETIME(6) NOT NULL, user_id BINARY(16) NOT NULL, INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE task (id BINARY(16) NOT NULL, name VARCHAR(255) DEFAULT NULL, dead_line DATETIME(6) DEFAULT NULL, slug VARCHAR(255) NOT NULL, topic_id INT NOT NULL, UNIQUE INDEX UNIQ_527EDB25989D9B62 (slug), INDEX IDX_527EDB251F55203D (topic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE task_food_topic (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE task_topic (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE toy_product (for_kids_more_than INT NOT NULL, id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (email_auth_code VARCHAR(255) DEFAULT NULL, id BINARY(16) NOT NULL, api_token VARCHAR(255) DEFAULT NULL, totp_secret VARCHAR(255) DEFAULT NULL, google_secret VARCHAR(255) DEFAULT NULL, backup_codes JSON DEFAULT NULL, trusted_version INT NOT NULL, email VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, switch_user_able TINYINT(1) NOT NULL, passport_id INT NOT NULL, git_hub_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D6494C8796 (git_hub_id), UNIQUE INDEX UNIQ_USER_EMAIL (email), UNIQUE INDEX UNIQ_USER_PASSPORT (passport_id), UNIQUE INDEX UNIQ_USER_API_TOKEN (api_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user_passport (id INT AUTO_INCREMENT NOT NULL, remember_me_updated_at DATETIME(6) NOT NULL, last_name VARCHAR(255) NOT NULL, timezone VARCHAR(30) DEFAULT NULL, lang VARCHAR(10) DEFAULT NULL, banned TINYINT(1) NOT NULL, updated_at DATETIME(6) DEFAULT NULL, created_at DATETIME(6) NOT NULL, first_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME(6) NOT NULL, available_at DATETIME(6) NOT NULL, delivered_at DATETIME(6) DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE cache_items (item_id VARBINARY(255) NOT NULL, item_data MEDIUMBLOB NOT NULL, item_lifetime INT UNSIGNED DEFAULT NULL, item_time INT UNSIGNED NOT NULL, PRIMARY KEY(item_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE lock_keys (key_id VARCHAR(64) NOT NULL, key_token VARCHAR(44) NOT NULL, key_expiration INT UNSIGNED NOT NULL, PRIMARY KEY(key_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE avatar ADD CONSTRAINT FK_1677722FBF396750 FOREIGN KEY (id) REFERENCES media (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE food_product ADD CONSTRAINT FK_9CD5D895BF396750 FOREIGN KEY (id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE furniture_product ADD CONSTRAINT FK_56AAF15BBF396750 FOREIGN KEY (id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FBF396750 FOREIGN KEY (id) REFERENCES media (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADABF410D0 FOREIGN KEY (passport_id) REFERENCES product_passport (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB251F55203D FOREIGN KEY (topic_id) REFERENCES task_topic (id)');
        $this->addSql('ALTER TABLE task_food_topic ADD CONSTRAINT FK_7032505EBF396750 FOREIGN KEY (id) REFERENCES task_topic (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE toy_product ADD CONSTRAINT FK_9BB08057BF396750 FOREIGN KEY (id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649ABF410D0 FOREIGN KEY (passport_id) REFERENCES user_passport (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6494C8796 FOREIGN KEY (git_hub_id) REFERENCES git_hub (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avatar DROP FOREIGN KEY FK_1677722FBF396750');
        $this->addSql('ALTER TABLE food_product DROP FOREIGN KEY FK_9CD5D895BF396750');
        $this->addSql('ALTER TABLE furniture_product DROP FOREIGN KEY FK_56AAF15BBF396750');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FBF396750');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADABF410D0');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADA76ED395');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB251F55203D');
        $this->addSql('ALTER TABLE task_food_topic DROP FOREIGN KEY FK_7032505EBF396750');
        $this->addSql('ALTER TABLE toy_product DROP FOREIGN KEY FK_9BB08057BF396750');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649ABF410D0');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6494C8796');
        $this->addSql('DROP TABLE app_easy_admin_cinema');
        $this->addSql('DROP TABLE app_easy_admin_comment');
        $this->addSql('DROP TABLE avatar');
        $this->addSql('DROP TABLE food_product');
        $this->addSql('DROP TABLE furniture_product');
        $this->addSql('DROP TABLE git_hub');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE machine');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_passport');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE task_food_topic');
        $this->addSql('DROP TABLE task_topic');
        $this->addSql('DROP TABLE toy_product');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_passport');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('DROP TABLE cache_items');
        $this->addSql('DROP TABLE lock_keys');
    }
}
