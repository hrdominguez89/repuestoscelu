<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240506202418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT fk_e52ffdee5080ecde');
        $this->addSql('DROP SEQUENCE historical_price_cost_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE inventory_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE warehouses_id_seq CASCADE');
        $this->addSql('ALTER TABLE inventory DROP CONSTRAINT fk_b12d4a365080ecde');
        $this->addSql('DROP TABLE warehouses');
        $this->addSql('DROP TABLE historical_price_cost');
        $this->addSql('DROP TABLE inventory');
        $this->addSql('DROP INDEX idx_e52ffdee5080ecde');
        $this->addSql('ALTER TABLE orders DROP warehouse_id');
        $this->addSql('ALTER TABLE product_admin_inventory ADD committed INT NOT NULL');
        $this->addSql('ALTER TABLE product_admin_inventory ADD sold INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE historical_price_cost_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE inventory_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE warehouses_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE warehouses (id INT NOT NULL, id3pl INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE historical_price_cost (id INT NOT NULL, cost DOUBLE PRECISION NOT NULL, price DOUBLE PRECISION DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE inventory (id INT NOT NULL, warehouse_id INT NOT NULL, id3pl INT NOT NULL, client_id INT NOT NULL, cod VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_b12d4a365080ecde ON inventory (warehouse_id)');
        $this->addSql('ALTER TABLE inventory ADD CONSTRAINT fk_b12d4a365080ecde FOREIGN KEY (warehouse_id) REFERENCES warehouses (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD warehouse_id INT NOT NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT fk_e52ffdee5080ecde FOREIGN KEY (warehouse_id) REFERENCES warehouses (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_e52ffdee5080ecde ON orders (warehouse_id)');
        $this->addSql('ALTER TABLE product_admin_inventory DROP committed');
        $this->addSql('ALTER TABLE product_admin_inventory DROP sold');
    }
}
