<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240419114208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_brand ALTER visible SET DEFAULT false');
        $this->addSql('ALTER TABLE mia_category ALTER visible SET DEFAULT false');
        $this->addSql('ALTER TABLE mia_product ALTER color_id DROP NOT NULL');
        $this->addSql('ALTER TABLE mia_sub_category ALTER visible SET DEFAULT false');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_category ALTER visible SET DEFAULT true');
        $this->addSql('ALTER TABLE mia_brand ALTER visible SET DEFAULT true');
        $this->addSql('ALTER TABLE mia_product ALTER color_id SET NOT NULL');
        $this->addSql('ALTER TABLE mia_sub_category ALTER visible SET DEFAULT true');
    }
}
