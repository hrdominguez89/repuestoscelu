<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240409232000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_customer ALTER verification_code TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE mia_customer ALTER policies_agree SET DEFAULT true');
        $this->addSql('COMMENT ON COLUMN mia_customer.verification_code IS NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_customer ALTER verification_code TYPE UUID');
        $this->addSql('ALTER TABLE mia_customer ALTER policies_agree DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN mia_customer.verification_code IS \'(DC2Type:uuid)\'');
    }
}
