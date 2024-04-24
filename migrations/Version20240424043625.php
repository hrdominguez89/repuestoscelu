<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240424043625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_8ee83afb799a3652');
        $this->addSql('ALTER TABLE mia_brand DROP nomenclature');
        $this->addSql('DROP INDEX uniq_16112475799a3652');
        $this->addSql('ALTER TABLE mia_category DROP image');
        $this->addSql('ALTER TABLE mia_category DROP description_es');
        $this->addSql('ALTER TABLE mia_category DROP nomenclature');
        $this->addSql('ALTER TABLE mia_category DROP description_en');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_category ADD image TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_category ADD description_es TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_category ADD nomenclature VARCHAR(3) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_category ADD description_en TEXT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX uniq_16112475799a3652 ON mia_category (nomenclature)');
        $this->addSql('ALTER TABLE mia_brand ADD nomenclature VARCHAR(3) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX uniq_8ee83afb799a3652 ON mia_brand (nomenclature)');
    }
}
