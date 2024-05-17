<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240516160325 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders_products DROP CONSTRAINT fk_749c879cd8d98c7');
        $this->addSql('DROP INDEX idx_749c879cd8d98c7');
        $this->addSql('ALTER TABLE orders_products RENAME COLUMN number_order_id TO order_number_id');
        $this->addSql('ALTER TABLE orders_products ADD CONSTRAINT FK_749C879C8C26A5E8 FOREIGN KEY (order_number_id) REFERENCES orders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_749C879C8C26A5E8 ON orders_products (order_number_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE orders_products DROP CONSTRAINT FK_749C879C8C26A5E8');
        $this->addSql('DROP INDEX IDX_749C879C8C26A5E8');
        $this->addSql('ALTER TABLE orders_products RENAME COLUMN order_number_id TO number_order_id');
        $this->addSql('ALTER TABLE orders_products ADD CONSTRAINT fk_749c879cd8d98c7 FOREIGN KEY (number_order_id) REFERENCES orders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_749c879cd8d98c7 ON orders_products (number_order_id)');
    }
}
