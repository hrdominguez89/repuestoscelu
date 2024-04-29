<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240429051032 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE dispatch_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE dispatch (id INT NOT NULL, sale_point_id BIGINT NOT NULL, status_id INT NOT NULL, description TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, modified_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8612665ADE5C2208 ON dispatch (sale_point_id)');
        $this->addSql('CREATE INDEX IDX_8612665A6BF700BD ON dispatch (status_id)');
        $this->addSql('ALTER TABLE dispatch ADD CONSTRAINT FK_8612665ADE5C2208 FOREIGN KEY (sale_point_id) REFERENCES mia_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE dispatch ADD CONSTRAINT FK_8612665A6BF700BD FOREIGN KEY (status_id) REFERENCES dispatch_status_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE dispatch_id_seq CASCADE');
        $this->addSql('ALTER TABLE dispatch DROP CONSTRAINT FK_8612665ADE5C2208');
        $this->addSql('ALTER TABLE dispatch DROP CONSTRAINT FK_8612665A6BF700BD');
        $this->addSql('DROP TABLE dispatch');
    }
}
