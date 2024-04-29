<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240429053316 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE product_sale_point_inventory_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE product_sale_point_inventory (id INT NOT NULL, product_sale_point_id INT NOT NULL, on_hand INT NOT NULL, available INT NOT NULL, committed INT NOT NULL, sold INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3292ADE14B13EFA ON product_sale_point_inventory (product_sale_point_id)');
        $this->addSql('ALTER TABLE product_sale_point_inventory ADD CONSTRAINT FK_3292ADE14B13EFA FOREIGN KEY (product_sale_point_id) REFERENCES products_sales_points (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE product_sale_point_inventory_id_seq CASCADE');
        $this->addSql('ALTER TABLE product_sale_point_inventory DROP CONSTRAINT FK_3292ADE14B13EFA');
        $this->addSql('DROP TABLE product_sale_point_inventory');
    }
}
