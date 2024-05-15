<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240515173357 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE actions_product_type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE history_product_stock_updated_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_newsletter_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_paypal_id_seq CASCADE');
        $this->addSql('ALTER TABLE history_product_stock_updated DROP CONSTRAINT fk_9b02ab2a9d32f035');
        $this->addSql('DROP TABLE mia_paypal');
        $this->addSql('DROP TABLE mia_newsletter');
        $this->addSql('DROP TABLE actions_product_type');
        $this->addSql('DROP TABLE history_product_stock_updated');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE actions_product_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE history_product_stock_updated_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_newsletter_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_paypal_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE mia_paypal (id BIGINT NOT NULL, client_id VARCHAR(255) DEFAULT NULL, client_secret VARCHAR(255) DEFAULT NULL, client_id_sand_box VARCHAR(255) DEFAULT NULL, client_secret_sand_box VARCHAR(255) DEFAULT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, active BOOLEAN NOT NULL, sand_box BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE mia_newsletter (id BIGINT NOT NULL, email VARCHAR(100) NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_a644b238e7927c74 ON mia_newsletter (email)');
        $this->addSql('CREATE TABLE actions_product_type (id INT NOT NULL, name VARCHAR(20) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE history_product_stock_updated (id INT NOT NULL, action_id INT NOT NULL, onhand INT NOT NULL, commited INT NOT NULL, incomming INT NOT NULL, available INT NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_9b02ab2a9d32f035 ON history_product_stock_updated (action_id)');
        $this->addSql('ALTER TABLE history_product_stock_updated ADD CONSTRAINT fk_9b02ab2a9d32f035 FOREIGN KEY (action_id) REFERENCES actions_product_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
