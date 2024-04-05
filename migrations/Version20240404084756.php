<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240404084756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE api_clients ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE cities ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE countries ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE customer_addresses ALTER registration_date DROP DEFAULT');
        $this->addSql('ALTER TABLE debit_credit_notes_files ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE email_queue ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE guide_numbers ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE historical_price_cost DROP CONSTRAINT fk_73ebd2b37d182d95');
        $this->addSql('DROP INDEX idx_73ebd2b37d182d95');
        $this->addSql('ALTER TABLE historical_price_cost DROP created_by_user_id');
        $this->addSql('ALTER TABLE historical_price_cost ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE history_product_stock_updated ALTER updated_at DROP DEFAULT');
        $this->addSql('ALTER TABLE mia_brand ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE mia_category ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE mia_customer DROP CONSTRAINT fk_9164b3bde71f8633');
        $this->addSql('DROP INDEX idx_9164b3bde71f8633');
        $this->addSql('ALTER TABLE mia_customer DROP registration_user_id');
        $this->addSql('ALTER TABLE mia_customer ALTER registration_date DROP DEFAULT');
        $this->addSql('ALTER TABLE mia_favorite_product ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE mia_product ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE mia_sub_category ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE mia_tag ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE orders ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE payments_files ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE payments_received_files ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE payments_transactions_codes ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE product_discount ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE shopping_cart ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE warehouses ALTER created_at DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE payments_transactions_codes ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE history_product_stock_updated ALTER updated_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE mia_category ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE countries ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE product_discount ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE guide_numbers ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE mia_favorite_product ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE warehouses ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE mia_brand ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE historical_price_cost ADD created_by_user_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE historical_price_cost ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE historical_price_cost ADD CONSTRAINT fk_73ebd2b37d182d95 FOREIGN KEY (created_by_user_id) REFERENCES mia_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_73ebd2b37d182d95 ON historical_price_cost (created_by_user_id)');
        $this->addSql('ALTER TABLE debit_credit_notes_files ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE shopping_cart ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE mia_product ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE orders ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE email_queue ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE payments_files ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE mia_customer ADD registration_user_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ALTER registration_date SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE mia_customer ADD CONSTRAINT fk_9164b3bde71f8633 FOREIGN KEY (registration_user_id) REFERENCES mia_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_9164b3bde71f8633 ON mia_customer (registration_user_id)');
        $this->addSql('ALTER TABLE api_clients ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE mia_tag ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE mia_sub_category ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE payments_received_files ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE cities ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE customer_addresses ALTER registration_date SET DEFAULT CURRENT_TIMESTAMP');
    }
}
