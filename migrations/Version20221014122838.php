<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221014122838 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_tag (product_id BIGINT NOT NULL, tag_id BIGINT NOT NULL, PRIMARY KEY(product_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_E3A6E39C4584665A ON product_tag (product_id)');
        $this->addSql('CREATE INDEX IDX_E3A6E39CBAD26311 ON product_tag (tag_id)');
        $this->addSql('ALTER TABLE product_tag ADD CONSTRAINT FK_E3A6E39C4584665A FOREIGN KEY (product_id) REFERENCES mia_product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_tag ADD CONSTRAINT FK_E3A6E39CBAD26311 FOREIGN KEY (tag_id) REFERENCES mia_tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD visible BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_tag ADD visible BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_tag ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_tag ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE product_tag DROP CONSTRAINT FK_E3A6E39C4584665A');
        $this->addSql('ALTER TABLE product_tag DROP CONSTRAINT FK_E3A6E39CBAD26311');
        $this->addSql('DROP TABLE product_tag');
        $this->addSql('ALTER TABLE mia_product DROP visible');
        $this->addSql('ALTER TABLE mia_tag DROP visible');
        $this->addSql('ALTER TABLE mia_tag DROP created_at');
        $this->addSql('ALTER TABLE mia_tag DROP updated_at');
    }
}
