<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240428072356 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE stock_product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE stock_product (id INT NOT NULL, product_id BIGINT NOT NULL, stock INT NOT NULL, cost DOUBLE PRECISION NOT NULL, description TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CAEC140E4584665A ON stock_product (product_id)');
        $this->addSql('ALTER TABLE stock_product ADD CONSTRAINT FK_CAEC140E4584665A FOREIGN KEY (product_id) REFERENCES mia_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE stock_product_id_seq CASCADE');
        $this->addSql('ALTER TABLE stock_product DROP CONSTRAINT FK_CAEC140E4584665A');
        $this->addSql('DROP TABLE stock_product');
    }
}
