<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240409180835 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_customer DROP CONSTRAINT fk_9164b3bdf0fa8e40');
        $this->addSql('DROP INDEX idx_9164b3bdf0fa8e40');
        $this->addSql('ALTER TABLE mia_customer ADD code_area INT NOT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD street_address VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD number_address VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD floor_apartment VARCHAR(15) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD policies_agree BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE mia_customer DROP customer_type_role_id');
        $this->addSql('ALTER TABLE mia_customer DROP phone');
        $this->addSql('ALTER TABLE mia_customer DROP identity_type');
        $this->addSql('ALTER TABLE mia_customer ALTER password DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_customer ADD customer_type_role_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD phone VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD identity_type VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer DROP code_area');
        $this->addSql('ALTER TABLE mia_customer DROP street_address');
        $this->addSql('ALTER TABLE mia_customer DROP number_address');
        $this->addSql('ALTER TABLE mia_customer DROP floor_apartment');
        $this->addSql('ALTER TABLE mia_customer DROP policies_agree');
        $this->addSql('ALTER TABLE mia_customer ALTER password SET NOT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD CONSTRAINT fk_9164b3bdf0fa8e40 FOREIGN KEY (customer_type_role_id) REFERENCES customers_types_roles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_9164b3bdf0fa8e40 ON mia_customer (customer_type_role_id)');
    }
}
