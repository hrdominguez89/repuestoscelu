<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240409153327 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer_addresses DROP CONSTRAINT fk_c4378d0c9395c3f3');
        $this->addSql('DROP INDEX idx_c4378d0c9395c3f3');
        $this->addSql('ALTER TABLE customer_addresses DROP customer_id');
        $this->addSql('ALTER TABLE mia_customer DROP CONSTRAINT fk_9164b3bd37a4f92f');
        $this->addSql('ALTER TABLE mia_customer DROP CONSTRAINT fk_9164b3bd442da0e1');
        $this->addSql('DROP INDEX idx_9164b3bd442da0e1');
        $this->addSql('DROP INDEX idx_9164b3bd37a4f92f');
        $this->addSql('ALTER TABLE mia_customer DROP gender_type_id');
        $this->addSql('ALTER TABLE mia_customer DROP country_phone_code_id');
        $this->addSql('ALTER TABLE mia_customer DROP state_code_cel_phone');
        $this->addSql('ALTER TABLE mia_customer DROP date_of_birth');
        $this->addSql('ALTER TABLE mia_customer DROP google_oauth_uid');
        $this->addSql('ALTER TABLE mia_customer DROP url_facebook');
        $this->addSql('ALTER TABLE mia_customer DROP url_instagram');
        $this->addSql('ALTER TABLE recipients DROP CONSTRAINT fk_146632c49395c3f3');
        $this->addSql('DROP INDEX idx_146632c49395c3f3');
        $this->addSql('ALTER TABLE recipients DROP customer_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE recipients ADD customer_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE recipients ADD CONSTRAINT fk_146632c49395c3f3 FOREIGN KEY (customer_id) REFERENCES mia_customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_146632c49395c3f3 ON recipients (customer_id)');
        $this->addSql('ALTER TABLE mia_customer ADD gender_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD country_phone_code_id INT NOT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD state_code_cel_phone VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD date_of_birth DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD google_oauth_uid BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD url_facebook VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD url_instagram VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD CONSTRAINT fk_9164b3bd37a4f92f FOREIGN KEY (gender_type_id) REFERENCES gender_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_customer ADD CONSTRAINT fk_9164b3bd442da0e1 FOREIGN KEY (country_phone_code_id) REFERENCES countries (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_9164b3bd442da0e1 ON mia_customer (country_phone_code_id)');
        $this->addSql('CREATE INDEX idx_9164b3bd37a4f92f ON mia_customer (gender_type_id)');
        $this->addSql('ALTER TABLE customer_addresses ADD customer_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE customer_addresses ADD CONSTRAINT fk_c4378d0c9395c3f3 FOREIGN KEY (customer_id) REFERENCES mia_customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_c4378d0c9395c3f3 ON customer_addresses (customer_id)');
    }
}
