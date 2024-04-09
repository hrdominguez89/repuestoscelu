<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240409154334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_customer ADD state_id INT NOT NULL');
        $this->addSql('ALTER TABLE mia_customer DROP state_code_phone');
        $this->addSql('ALTER TABLE mia_customer ADD CONSTRAINT FK_9164B3BD5D83CC1 FOREIGN KEY (state_id) REFERENCES states (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9164B3BD5D83CC1 ON mia_customer (state_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_customer DROP CONSTRAINT FK_9164B3BD5D83CC1');
        $this->addSql('DROP INDEX IDX_9164B3BD5D83CC1');
        $this->addSql('ALTER TABLE mia_customer ADD state_code_phone VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer DROP state_id');
    }
}
