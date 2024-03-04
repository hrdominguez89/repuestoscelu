<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230205161436 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_product ADD tag_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD tag_expires BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD tag_expiration_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA37BAD26311 FOREIGN KEY (tag_id) REFERENCES mia_tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_540DEA37BAD26311 ON mia_product (tag_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA37BAD26311');
        $this->addSql('DROP INDEX IDX_540DEA37BAD26311');
        $this->addSql('ALTER TABLE mia_product DROP tag_id');
        $this->addSql('ALTER TABLE mia_product DROP tag_expires');
        $this->addSql('ALTER TABLE mia_product DROP tag_expiration_date');
    }
}
