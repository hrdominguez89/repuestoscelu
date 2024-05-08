<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240508183429 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE OR REPLACE FUNCTION remove_accents(input_str text) RETURNS text AS $$
            DECLARE
                output_str text;
            BEGIN
                output_str := translate(input_str,
                                        \'áéíóúÁÉÍÓÚüÜ\',
                                        \'aeiouAEIOUuU\');
                RETURN output_str;
            END;
            $$ LANGUAGE plpgsql;');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
    }
}
