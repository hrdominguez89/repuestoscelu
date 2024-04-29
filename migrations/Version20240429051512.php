<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240429051512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE product_dispatch_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE product_dispatch (id INT NOT NULL, dispatch_id INT NOT NULL, product_id BIGINT NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4DA20CCDC774D3B9 ON product_dispatch (dispatch_id)');
        $this->addSql('CREATE INDEX IDX_4DA20CCD4584665A ON product_dispatch (product_id)');
        $this->addSql('ALTER TABLE product_dispatch ADD CONSTRAINT FK_4DA20CCDC774D3B9 FOREIGN KEY (dispatch_id) REFERENCES dispatch (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_dispatch ADD CONSTRAINT FK_4DA20CCD4584665A FOREIGN KEY (product_id) REFERENCES mia_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE product_dispatch_id_seq CASCADE');
        $this->addSql('ALTER TABLE product_dispatch DROP CONSTRAINT FK_4DA20CCDC774D3B9');
        $this->addSql('ALTER TABLE product_dispatch DROP CONSTRAINT FK_4DA20CCD4584665A');
        $this->addSql('DROP TABLE product_dispatch');
    }
}
