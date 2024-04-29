<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240429041920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE product_admin_inventory_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE product_admin_inventory (id INT NOT NULL, product_id BIGINT NOT NULL, on_hand INT NOT NULL, available INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6A514A994584665A ON product_admin_inventory (product_id)');
        $this->addSql('ALTER TABLE product_admin_inventory ADD CONSTRAINT FK_6A514A994584665A FOREIGN KEY (product_id) REFERENCES mia_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE product_admin_inventory_id_seq CASCADE');
        $this->addSql('ALTER TABLE product_admin_inventory DROP CONSTRAINT FK_6A514A994584665A');
        $this->addSql('DROP TABLE product_admin_inventory');
    }
}
