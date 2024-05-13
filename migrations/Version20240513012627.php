<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240513012627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shopping_cart DROP CONSTRAINT fk_72aad4f64584665a');
        $this->addSql('DROP INDEX idx_72aad4f64584665a');
        $this->addSql('ALTER TABLE shopping_cart ADD products_sales_points_id INT NOT NULL');
        $this->addSql('ALTER TABLE shopping_cart DROP product_id');
        $this->addSql('ALTER TABLE shopping_cart ADD CONSTRAINT FK_72AAD4F6ABCBD6DD FOREIGN KEY (products_sales_points_id) REFERENCES products_sales_points (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_72AAD4F6ABCBD6DD ON shopping_cart (products_sales_points_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE shopping_cart DROP CONSTRAINT FK_72AAD4F6ABCBD6DD');
        $this->addSql('DROP INDEX IDX_72AAD4F6ABCBD6DD');
        $this->addSql('ALTER TABLE shopping_cart ADD product_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE shopping_cart DROP products_sales_points_id');
        $this->addSql('ALTER TABLE shopping_cart ADD CONSTRAINT fk_72aad4f64584665a FOREIGN KEY (product_id) REFERENCES mia_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_72aad4f64584665a ON shopping_cart (product_id)');
    }
}
