<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240516043945 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT fk_e52ffdee3d7dc734');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT fk_e52ffdee7f6f56b0');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT fk_e52ffdee8824f200');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT fk_e52ffdeef695530d');
        $this->addSql('DROP INDEX idx_e52ffdeef695530d');
        $this->addSql('DROP INDEX idx_e52ffdee8824f200');
        $this->addSql('DROP INDEX idx_e52ffdee7f6f56b0');
        $this->addSql('DROP INDEX idx_e52ffdee3d7dc734');
        $this->addSql('ALTER TABLE orders ADD customer_state_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD customer_city_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD customer_street_address TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD customer_postal_code VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD customer_number_address TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD customer_floor_apartment TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders DROP bill_state_id');
        $this->addSql('ALTER TABLE orders DROP bill_city_id');
        $this->addSql('ALTER TABLE orders DROP receiver_state_id');
        $this->addSql('ALTER TABLE orders DROP receiver_city_id');
        $this->addSql('ALTER TABLE orders DROP bill_address_order');
        $this->addSql('ALTER TABLE orders DROP bill_postal_code');
        $this->addSql('ALTER TABLE orders DROP bill_additional_info');
        $this->addSql('ALTER TABLE orders DROP subtotal');
        $this->addSql('ALTER TABLE orders DROP total_product_discount');
        $this->addSql('ALTER TABLE orders DROP promotional_code_discount');
        $this->addSql('ALTER TABLE orders DROP tax');
        $this->addSql('ALTER TABLE orders DROP shipping_cost');
        $this->addSql('ALTER TABLE orders DROP shipping_discount');
        $this->addSql('ALTER TABLE orders DROP paypal_service_cost');
        $this->addSql('ALTER TABLE orders DROP receiver_name');
        $this->addSql('ALTER TABLE orders DROP receiver_document_type');
        $this->addSql('ALTER TABLE orders DROP receiver_document');
        $this->addSql('ALTER TABLE orders DROP receiver_phone_cell');
        $this->addSql('ALTER TABLE orders DROP receiver_phone_home');
        $this->addSql('ALTER TABLE orders DROP receiver_email');
        $this->addSql('ALTER TABLE orders DROP receiver_address');
        $this->addSql('ALTER TABLE orders DROP receiver_cod_zip');
        $this->addSql('ALTER TABLE orders DROP receiver_additional_info');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE291D7FEE FOREIGN KEY (customer_state_id) REFERENCES states (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE9771C611 FOREIGN KEY (customer_city_id) REFERENCES cities (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E52FFDEE291D7FEE ON orders (customer_state_id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEE9771C611 ON orders (customer_city_id)');
        $this->addSql('ALTER TABLE orders_products RENAME COLUMN cod TO code');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEE291D7FEE');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEE9771C611');
        $this->addSql('DROP INDEX IDX_E52FFDEE291D7FEE');
        $this->addSql('DROP INDEX IDX_E52FFDEE9771C611');
        $this->addSql('ALTER TABLE orders ADD bill_state_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD bill_city_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD receiver_state_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD receiver_city_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD bill_address_order TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD bill_additional_info TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD subtotal DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD total_product_discount DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD promotional_code_discount DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD tax DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD shipping_cost DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD shipping_discount DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD paypal_service_cost DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD receiver_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD receiver_document_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD receiver_document VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD receiver_phone_cell VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD receiver_phone_home VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD receiver_email VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD receiver_address TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD receiver_cod_zip VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD receiver_additional_info TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders DROP customer_state_id');
        $this->addSql('ALTER TABLE orders DROP customer_city_id');
        $this->addSql('ALTER TABLE orders DROP customer_street_address');
        $this->addSql('ALTER TABLE orders DROP customer_number_address');
        $this->addSql('ALTER TABLE orders DROP customer_floor_apartment');
        $this->addSql('ALTER TABLE orders RENAME COLUMN customer_postal_code TO bill_postal_code');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT fk_e52ffdee3d7dc734 FOREIGN KEY (receiver_state_id) REFERENCES states (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT fk_e52ffdee7f6f56b0 FOREIGN KEY (bill_city_id) REFERENCES cities (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT fk_e52ffdee8824f200 FOREIGN KEY (bill_state_id) REFERENCES states (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT fk_e52ffdeef695530d FOREIGN KEY (receiver_city_id) REFERENCES cities (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_e52ffdeef695530d ON orders (receiver_city_id)');
        $this->addSql('CREATE INDEX idx_e52ffdee8824f200 ON orders (bill_state_id)');
        $this->addSql('CREATE INDEX idx_e52ffdee7f6f56b0 ON orders (bill_city_id)');
        $this->addSql('CREATE INDEX idx_e52ffdee3d7dc734 ON orders (receiver_state_id)');
        $this->addSql('ALTER TABLE orders_products RENAME COLUMN code TO cod');
    }
}
