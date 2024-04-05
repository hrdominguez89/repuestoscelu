<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240404085412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_user ADD state_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_user ADD city_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_user ADD CONSTRAINT FK_E5DEEE855D83CC1 FOREIGN KEY (state_id) REFERENCES states (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_user ADD CONSTRAINT FK_E5DEEE858BAC62AF FOREIGN KEY (city_id) REFERENCES cities (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E5DEEE855D83CC1 ON mia_user (state_id)');
        $this->addSql('CREATE INDEX IDX_E5DEEE858BAC62AF ON mia_user (city_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_user DROP CONSTRAINT FK_E5DEEE855D83CC1');
        $this->addSql('ALTER TABLE mia_user DROP CONSTRAINT FK_E5DEEE858BAC62AF');
        $this->addSql('DROP INDEX IDX_E5DEEE855D83CC1');
        $this->addSql('DROP INDEX IDX_E5DEEE858BAC62AF');
        $this->addSql('ALTER TABLE mia_user DROP state_id');
        $this->addSql('ALTER TABLE mia_user DROP city_id');
    }
}
