<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240516034709 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders_products ADD products_sales_points_id INT NOT NULL');
        $this->addSql('ALTER TABLE orders_products ADD CONSTRAINT FK_749C879CABCBD6DD FOREIGN KEY (products_sales_points_id) REFERENCES products_sales_points (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_749C879CABCBD6DD ON orders_products (products_sales_points_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE orders_products DROP CONSTRAINT FK_749C879CABCBD6DD');
        $this->addSql('DROP INDEX IDX_749C879CABCBD6DD');
        $this->addSql('ALTER TABLE orders_products DROP products_sales_points_id');
    }
}
