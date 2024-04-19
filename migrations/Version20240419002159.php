<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240419002159 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE historical_price_cost DROP CONSTRAINT fk_73ebd2b34584665a');
        $this->addSql('DROP INDEX idx_73ebd2b34584665a');
        $this->addSql('ALTER TABLE historical_price_cost DROP product_id');
        $this->addSql('ALTER TABLE history_product_stock_updated DROP CONSTRAINT fk_9b02ab2a4584665a');
        $this->addSql('DROP INDEX idx_9b02ab2a4584665a');
        $this->addSql('ALTER TABLE history_product_stock_updated DROP product_id');
        $this->addSql('ALTER TABLE items_guide_number DROP CONSTRAINT fk_bfb5e8ba4584665a');
        $this->addSql('DROP INDEX idx_bfb5e8ba4584665a');
        $this->addSql('ALTER TABLE items_guide_number DROP product_id');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT fk_540dea37bad26311');
        $this->addSql('DROP INDEX uniq_540dea37f9038c4');
        $this->addSql('DROP INDEX idx_540dea37bad26311');
        $this->addSql('ALTER TABLE mia_product DROP tag_id');
        $this->addSql('ALTER TABLE mia_product DROP sku');
        $this->addSql('ALTER TABLE mia_product DROP part_number');
        $this->addSql('ALTER TABLE mia_product DROP onhand');
        $this->addSql('ALTER TABLE mia_product DROP commited');
        $this->addSql('ALTER TABLE mia_product DROP incomming');
        $this->addSql('ALTER TABLE mia_product DROP available');
        $this->addSql('ALTER TABLE mia_product DROP description_en');
        $this->addSql('ALTER TABLE mia_product DROP long_description_en');
        $this->addSql('ALTER TABLE mia_product DROP weight');
        $this->addSql('ALTER TABLE mia_product DROP tag_expires');
        $this->addSql('ALTER TABLE mia_product DROP tag_expiration_date');
        $this->addSql('ALTER TABLE mia_product DROP rating');
        $this->addSql('UPDATE mia_product set reviews = (SELECT id FROM mia_user WHERE mia_user.role_id = 1 limit 1)');
        $this->addSql('ALTER TABLE mia_product RENAME COLUMN reviews TO sale_point_id');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA37DE5C2208 FOREIGN KEY (sale_point_id) REFERENCES mia_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_540DEA37DE5C2208 ON mia_product (sale_point_id)');
        $this->addSql('ALTER TABLE product_discount DROP CONSTRAINT fk_2a50de994584665a');
        $this->addSql('DROP INDEX idx_2a50de994584665a');
        $this->addSql('ALTER TABLE product_discount DROP product_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE history_product_stock_updated ADD product_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE history_product_stock_updated ADD CONSTRAINT fk_9b02ab2a4584665a FOREIGN KEY (product_id) REFERENCES mia_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_9b02ab2a4584665a ON history_product_stock_updated (product_id)');
        $this->addSql('ALTER TABLE items_guide_number ADD product_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE items_guide_number ADD CONSTRAINT fk_bfb5e8ba4584665a FOREIGN KEY (product_id) REFERENCES mia_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_bfb5e8ba4584665a ON items_guide_number (product_id)');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA37DE5C2208');
        $this->addSql('DROP INDEX IDX_540DEA37DE5C2208');
        $this->addSql('ALTER TABLE mia_product ADD tag_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD sku VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE mia_product ADD part_number VARCHAR(15) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD onhand INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE mia_product ADD commited INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE mia_product ADD incomming INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE mia_product ADD available INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE mia_product ADD description_en TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD long_description_en TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD weight DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD tag_expires BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD tag_expiration_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD rating SMALLINT DEFAULT 5 NOT NULL');
        $this->addSql('ALTER TABLE mia_product RENAME COLUMN sale_point_id TO reviews');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT fk_540dea37bad26311 FOREIGN KEY (tag_id) REFERENCES mia_tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_540dea37f9038c4 ON mia_product (sku)');
        $this->addSql('CREATE INDEX idx_540dea37bad26311 ON mia_product (tag_id)');
        $this->addSql('ALTER TABLE product_discount ADD product_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE product_discount ADD CONSTRAINT fk_2a50de994584665a FOREIGN KEY (product_id) REFERENCES mia_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_2a50de994584665a ON product_discount (product_id)');
        $this->addSql('ALTER TABLE historical_price_cost ADD product_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE historical_price_cost ADD CONSTRAINT fk_73ebd2b34584665a FOREIGN KEY (product_id) REFERENCES mia_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_73ebd2b34584665a ON historical_price_cost (product_id)');
    }
}
