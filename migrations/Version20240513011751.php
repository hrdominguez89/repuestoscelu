<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240513011751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_favorite_product DROP CONSTRAINT fk_c5e63aa74584665a');
        $this->addSql('DROP INDEX idx_c5e63aa74584665a');
        $this->addSql('ALTER TABLE mia_favorite_product ADD products_sales_points_id INT NOT NULL');
        $this->addSql('ALTER TABLE mia_favorite_product DROP product_id');
        $this->addSql('ALTER TABLE mia_favorite_product ADD CONSTRAINT FK_C5E63AA7ABCBD6DD FOREIGN KEY (products_sales_points_id) REFERENCES products_sales_points (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C5E63AA7ABCBD6DD ON mia_favorite_product (products_sales_points_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_favorite_product DROP CONSTRAINT FK_C5E63AA7ABCBD6DD');
        $this->addSql('DROP INDEX IDX_C5E63AA7ABCBD6DD');
        $this->addSql('ALTER TABLE mia_favorite_product ADD product_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE mia_favorite_product DROP products_sales_points_id');
        $this->addSql('ALTER TABLE mia_favorite_product ADD CONSTRAINT fk_c5e63aa74584665a FOREIGN KEY (product_id) REFERENCES mia_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_c5e63aa74584665a ON mia_favorite_product (product_id)');
    }
}
