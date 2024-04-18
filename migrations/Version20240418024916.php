<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240418024916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE sections_home_id_seq CASCADE');
        $this->addSql('ALTER TABLE sections_home DROP CONSTRAINT fk_cbcadee2161bd1d0');
        $this->addSql('ALTER TABLE sections_home DROP CONSTRAINT fk_cbcadee21d87595');
        $this->addSql('ALTER TABLE sections_home DROP CONSTRAINT fk_cbcadee221c521e2');
        $this->addSql('ALTER TABLE sections_home DROP CONSTRAINT fk_cbcadee2419994a9');
        $this->addSql('ALTER TABLE sections_home DROP CONSTRAINT fk_cbcadee24ae7e3e');
        $this->addSql('ALTER TABLE sections_home DROP CONSTRAINT fk_cbcadee24d731c80');
        $this->addSql('ALTER TABLE sections_home DROP CONSTRAINT fk_cbcadee264f2cb75');
        $this->addSql('ALTER TABLE sections_home DROP CONSTRAINT fk_cbcadee26818435c');
        $this->addSql('ALTER TABLE sections_home DROP CONSTRAINT fk_cbcadee27647649b');
        $this->addSql('ALTER TABLE sections_home DROP CONSTRAINT fk_cbcadee27aadecb2');
        $this->addSql('ALTER TABLE sections_home DROP CONSTRAINT fk_cbcadee29c0f4d2c');
        $this->addSql('ALTER TABLE sections_home DROP CONSTRAINT fk_cbcadee2abd1bd1e');
        $this->addSql('ALTER TABLE sections_home DROP CONSTRAINT fk_cbcadee2b96412f0');
        $this->addSql('ALTER TABLE sections_home DROP CONSTRAINT fk_cbcadee2bc12195b');
        $this->addSql('ALTER TABLE sections_home DROP CONSTRAINT fk_cbcadee2d0a42439');
        $this->addSql('ALTER TABLE sections_home DROP CONSTRAINT fk_cbcadee2dc4eac10');
        $this->addSql('DROP TABLE sections_home');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE sections_home_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE sections_home (id INT NOT NULL, category1_section1_id BIGINT DEFAULT NULL, category2_section1_id BIGINT DEFAULT NULL, category3_section1_id BIGINT DEFAULT NULL, category1_section2_id BIGINT DEFAULT NULL, category2_section2_id BIGINT DEFAULT NULL, category3_section2_id BIGINT DEFAULT NULL, category1_section3_id BIGINT DEFAULT NULL, category2_section3_id BIGINT DEFAULT NULL, category3_section3_id BIGINT DEFAULT NULL, category1_section4_id BIGINT DEFAULT NULL, category2_section4_id BIGINT DEFAULT NULL, category3_section4_id BIGINT DEFAULT NULL, tag_section1_id BIGINT NOT NULL, tag_section2_id BIGINT NOT NULL, tag_section3_id BIGINT NOT NULL, tag_section4_id BIGINT NOT NULL, title_section1 VARCHAR(255) NOT NULL, title_section2 VARCHAR(255) NOT NULL, title_section3 VARCHAR(255) NOT NULL, title_section4 VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_cbcadee2dc4eac10 ON sections_home (category3_section3_id)');
        $this->addSql('CREATE INDEX idx_cbcadee2d0a42439 ON sections_home (tag_section3_id)');
        $this->addSql('CREATE INDEX idx_cbcadee2bc12195b ON sections_home (category1_section3_id)');
        $this->addSql('CREATE INDEX idx_cbcadee2b96412f0 ON sections_home (category2_section2_id)');
        $this->addSql('CREATE INDEX idx_cbcadee2abd1bd1e ON sections_home (category2_section1_id)');
        $this->addSql('CREATE INDEX idx_cbcadee29c0f4d2c ON sections_home (category2_section4_id)');
        $this->addSql('CREATE INDEX idx_cbcadee27aadecb2 ON sections_home (tag_section1_id)');
        $this->addSql('CREATE INDEX idx_cbcadee27647649b ON sections_home (category3_section1_id)');
        $this->addSql('CREATE INDEX idx_cbcadee26818435c ON sections_home (tag_section2_id)');
        $this->addSql('CREATE INDEX idx_cbcadee264f2cb75 ON sections_home (category3_section2_id)');
        $this->addSql('CREATE INDEX idx_cbcadee24d731c80 ON sections_home (tag_section4_id)');
        $this->addSql('CREATE INDEX idx_cbcadee24ae7e3e ON sections_home (category1_section2_id)');
        $this->addSql('CREATE INDEX idx_cbcadee2419994a9 ON sections_home (category3_section4_id)');
        $this->addSql('CREATE INDEX idx_cbcadee221c521e2 ON sections_home (category1_section4_id)');
        $this->addSql('CREATE INDEX idx_cbcadee21d87595 ON sections_home (category2_section3_id)');
        $this->addSql('CREATE INDEX idx_cbcadee2161bd1d0 ON sections_home (category1_section1_id)');
        $this->addSql('ALTER TABLE sections_home ADD CONSTRAINT fk_cbcadee2161bd1d0 FOREIGN KEY (category1_section1_id) REFERENCES mia_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sections_home ADD CONSTRAINT fk_cbcadee21d87595 FOREIGN KEY (category2_section3_id) REFERENCES mia_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sections_home ADD CONSTRAINT fk_cbcadee221c521e2 FOREIGN KEY (category1_section4_id) REFERENCES mia_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sections_home ADD CONSTRAINT fk_cbcadee2419994a9 FOREIGN KEY (category3_section4_id) REFERENCES mia_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sections_home ADD CONSTRAINT fk_cbcadee24ae7e3e FOREIGN KEY (category1_section2_id) REFERENCES mia_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sections_home ADD CONSTRAINT fk_cbcadee24d731c80 FOREIGN KEY (tag_section4_id) REFERENCES mia_tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sections_home ADD CONSTRAINT fk_cbcadee264f2cb75 FOREIGN KEY (category3_section2_id) REFERENCES mia_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sections_home ADD CONSTRAINT fk_cbcadee26818435c FOREIGN KEY (tag_section2_id) REFERENCES mia_tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sections_home ADD CONSTRAINT fk_cbcadee27647649b FOREIGN KEY (category3_section1_id) REFERENCES mia_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sections_home ADD CONSTRAINT fk_cbcadee27aadecb2 FOREIGN KEY (tag_section1_id) REFERENCES mia_tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sections_home ADD CONSTRAINT fk_cbcadee29c0f4d2c FOREIGN KEY (category2_section4_id) REFERENCES mia_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sections_home ADD CONSTRAINT fk_cbcadee2abd1bd1e FOREIGN KEY (category2_section1_id) REFERENCES mia_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sections_home ADD CONSTRAINT fk_cbcadee2b96412f0 FOREIGN KEY (category2_section2_id) REFERENCES mia_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sections_home ADD CONSTRAINT fk_cbcadee2bc12195b FOREIGN KEY (category1_section3_id) REFERENCES mia_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sections_home ADD CONSTRAINT fk_cbcadee2d0a42439 FOREIGN KEY (tag_section3_id) REFERENCES mia_tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sections_home ADD CONSTRAINT fk_cbcadee2dc4eac10 FOREIGN KEY (category3_section3_id) REFERENCES mia_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
