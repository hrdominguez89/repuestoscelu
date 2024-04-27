<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240426130710 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE historical_price_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE historical_price (id INT NOT NULL, product_sale_point_id INT NOT NULL, price DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6B17E85F4B13EFA ON historical_price (product_sale_point_id)');
        $this->addSql('ALTER TABLE historical_price ADD CONSTRAINT FK_6B17E85F4B13EFA FOREIGN KEY (product_sale_point_id) REFERENCES products_sales_points (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE historical_price_id_seq CASCADE');
        $this->addSql('ALTER TABLE historical_price DROP CONSTRAINT FK_6B17E85F4B13EFA');
        $this->addSql('DROP TABLE historical_price');
    }
}
