<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240424052153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE products_sales_points_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE products_sales_points (id INT NOT NULL, product_id BIGINT NOT NULL, sale_point_id BIGINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_62694B8F4584665A ON products_sales_points (product_id)');
        $this->addSql('CREATE INDEX IDX_62694B8FDE5C2208 ON products_sales_points (sale_point_id)');
        $this->addSql('ALTER TABLE products_sales_points ADD CONSTRAINT FK_62694B8F4584665A FOREIGN KEY (product_id) REFERENCES mia_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE products_sales_points ADD CONSTRAINT FK_62694B8FDE5C2208 FOREIGN KEY (sale_point_id) REFERENCES mia_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE products_sales_points_id_seq CASCADE');
        $this->addSql('ALTER TABLE products_sales_points DROP CONSTRAINT FK_62694B8F4584665A');
        $this->addSql('ALTER TABLE products_sales_points DROP CONSTRAINT FK_62694B8FDE5C2208');
        $this->addSql('DROP TABLE products_sales_points');
    }
}
