<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240426051112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE product_sale_point_tag_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE product_sale_point_tag (id INT NOT NULL, product_sale_point_id INT NOT NULL, tag_id BIGINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3531752C4B13EFA ON product_sale_point_tag (product_sale_point_id)');
        $this->addSql('CREATE INDEX IDX_3531752CBAD26311 ON product_sale_point_tag (tag_id)');
        $this->addSql('ALTER TABLE product_sale_point_tag ADD CONSTRAINT FK_3531752C4B13EFA FOREIGN KEY (product_sale_point_id) REFERENCES products_sales_points (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_sale_point_tag ADD CONSTRAINT FK_3531752CBAD26311 FOREIGN KEY (tag_id) REFERENCES mia_tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE product_sale_point_tag_id_seq CASCADE');
        $this->addSql('ALTER TABLE product_sale_point_tag DROP CONSTRAINT FK_3531752C4B13EFA');
        $this->addSql('ALTER TABLE product_sale_point_tag DROP CONSTRAINT FK_3531752CBAD26311');
        $this->addSql('DROP TABLE product_sale_point_tag');
    }
}
