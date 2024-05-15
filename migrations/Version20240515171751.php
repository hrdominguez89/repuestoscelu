<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240515171751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders_products DROP CONSTRAINT fk_749c879ce053ff00');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT fk_e52ffdee23048a57');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT fk_e52ffdeeb4b9e7e');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT fk_e52ffdeed991282d');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT fk_e52ffdee5b8a2b31');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT fk_e52ffdeee92f8f78');
        $this->addSql('DROP SEQUENCE communication_states_between_platforms_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE customer_addresses_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE customers_types_roles_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE guide_numbers_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE items_guide_number_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_coupon_discount_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_customer_coupon_discount_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_order_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_order_items_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE payments_transactions_codes_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE product_discount_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE shipping_types_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE recipients_id_seq CASCADE');
        $this->addSql('ALTER TABLE product_discount DROP CONSTRAINT fk_2a50de997d182d95');
        $this->addSql('ALTER TABLE mia_order DROP CONSTRAINT fk_6793503b9395c3f3');
        $this->addSql('ALTER TABLE items_guide_number DROP CONSTRAINT fk_bfb5e8ba7d263ef6');
        $this->addSql('ALTER TABLE mia_customer_coupon_discount DROP CONSTRAINT fk_eec086f79395c3f3');
        $this->addSql('ALTER TABLE guide_numbers DROP CONSTRAINT fk_438850d1d8d98c7');
        $this->addSql('ALTER TABLE mia_order_items DROP CONSTRAINT fk_dfe5ae9b8d9f6d38');
        $this->addSql('ALTER TABLE customer_addresses DROP CONSTRAINT fk_c4378d0c5d83cc1');
        $this->addSql('ALTER TABLE customer_addresses DROP CONSTRAINT fk_c4378d0c853dd935');
        $this->addSql('ALTER TABLE customer_addresses DROP CONSTRAINT fk_c4378d0c8bac62af');
        $this->addSql('ALTER TABLE customer_addresses DROP CONSTRAINT fk_c4378d0ce71f8633');
        $this->addSql('ALTER TABLE customer_addresses DROP CONSTRAINT fk_c4378d0cf92f3e70');
        $this->addSql('ALTER TABLE recipients DROP CONSTRAINT fk_146632c4f92f3e70');
        $this->addSql('ALTER TABLE recipients DROP CONSTRAINT fk_146632c45d83cc1');
        $this->addSql('ALTER TABLE recipients DROP CONSTRAINT fk_146632c48bac62af');
        $this->addSql('ALTER TABLE payments_transactions_codes DROP CONSTRAINT fk_eae13e988c26a5e8');
        $this->addSql('DROP TABLE product_discount');
        $this->addSql('DROP TABLE shipping_types');
        $this->addSql('DROP TABLE mia_order');
        $this->addSql('DROP TABLE items_guide_number');
        $this->addSql('DROP TABLE mia_customer_coupon_discount');
        $this->addSql('DROP TABLE communication_states_between_platforms');
        $this->addSql('DROP TABLE customers_types_roles');
        $this->addSql('DROP TABLE guide_numbers');
        $this->addSql('DROP TABLE mia_order_items');
        $this->addSql('DROP TABLE customer_addresses');
        $this->addSql('DROP TABLE mia_coupon_discount');
        $this->addSql('DROP TABLE recipients');
        $this->addSql('DROP TABLE payments_transactions_codes');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT fk_e52ffdee4a3d0927');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT fk_e52ffdeeba8b38b9');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT fk_e52ffdeed3fd0226');
        $this->addSql('DROP INDEX idx_e52ffdeee92f8f78');
        $this->addSql('DROP INDEX idx_e52ffdeed991282d');
        $this->addSql('DROP INDEX idx_e52ffdeed3fd0226');
        $this->addSql('DROP INDEX idx_e52ffdeeba8b38b9');
        $this->addSql('DROP INDEX idx_e52ffdeeb4b9e7e');
        $this->addSql('DROP INDEX idx_e52ffdee5b8a2b31');
        $this->addSql('DROP INDEX idx_e52ffdee4a3d0927');
        $this->addSql('DROP INDEX idx_e52ffdee23048a57');
        $this->addSql('ALTER TABLE orders ADD code_area_phone_customer VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE orders DROP customer_type_id');
        $this->addSql('ALTER TABLE orders DROP customer_phone_code_id');
        $this->addSql('ALTER TABLE orders DROP bill_address_id');
        $this->addSql('ALTER TABLE orders DROP biil_country_id');
        $this->addSql('ALTER TABLE orders DROP receiver_country_id');
        $this->addSql('ALTER TABLE orders DROP status_sent_crm_id');
        $this->addSql('ALTER TABLE orders DROP shipping_type_id');
        $this->addSql('ALTER TABLE orders DROP recipient_id');
        $this->addSql('ALTER TABLE orders DROP cel_phone_customer');
        $this->addSql('ALTER TABLE orders DROP customer_identity_type');
        $this->addSql('ALTER TABLE orders DROP international_shipping');
        $this->addSql('ALTER TABLE orders DROP shipping');
        $this->addSql('ALTER TABLE orders DROP inventory_id');
        $this->addSql('ALTER TABLE orders DROP error_message_crm');
        $this->addSql('ALTER TABLE orders DROP attempts_send_crm');
        $this->addSql('ALTER TABLE orders DROP sales_id_3pl');
        $this->addSql('ALTER TABLE orders_products DROP CONSTRAINT fk_749c879c4584665a');
        $this->addSql('DROP INDEX idx_749c879ce053ff00');
        $this->addSql('DROP INDEX idx_749c879c4584665a');
        $this->addSql('ALTER TABLE orders_products DROP product_id');
        $this->addSql('ALTER TABLE orders_products DROP product_discount_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE communication_states_between_platforms_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE customer_addresses_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE customers_types_roles_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE guide_numbers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE items_guide_number_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_coupon_discount_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_customer_coupon_discount_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_order_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_order_items_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE payments_transactions_codes_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product_discount_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE shipping_types_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE recipients_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE product_discount (id INT NOT NULL, created_by_user_id BIGINT NOT NULL, percentage_discount INT NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, product_limit BIGINT NOT NULL, used BIGINT DEFAULT 0 NOT NULL, active BOOLEAN DEFAULT true NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_2a50de997d182d95 ON product_discount (created_by_user_id)');
        $this->addSql('CREATE TABLE shipping_types (id INT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE mia_order (id BIGINT NOT NULL, customer_id BIGINT NOT NULL, checkout_id VARCHAR(255) DEFAULT NULL, checkout_status VARCHAR(255) DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, sub_total DOUBLE PRECISION DEFAULT NULL, total DOUBLE PRECISION DEFAULT NULL, shipping DOUBLE PRECISION DEFAULT NULL, handling DOUBLE PRECISION DEFAULT NULL, insurance DOUBLE PRECISION DEFAULT NULL, tax_total DOUBLE PRECISION DEFAULT NULL, shipping_discount DOUBLE PRECISION DEFAULT NULL, discount DOUBLE PRECISION DEFAULT NULL, quantity INT DEFAULT NULL, payment_method VARCHAR(100) DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, checkout_b_first_name VARCHAR(100) NOT NULL, checkout_b_last_name VARCHAR(100) NOT NULL, checkout_b_company_name VARCHAR(255) DEFAULT NULL, checkout_b_country_iso VARCHAR(10) NOT NULL, checkout_b_country VARCHAR(100) NOT NULL, checkout_b_street_address VARCHAR(500) NOT NULL, checkout_b_address VARCHAR(255) DEFAULT NULL, checkout_b_city VARCHAR(100) NOT NULL, checkout_b_state VARCHAR(100) NOT NULL, checkout_b_postcode VARCHAR(50) NOT NULL, checkout_b_email VARCHAR(100) NOT NULL, checkout_b_phone VARCHAR(100) NOT NULL, checkout_s_first_name VARCHAR(100) NOT NULL, checkout_s_last_name VARCHAR(100) NOT NULL, checkout_s_company_name VARCHAR(255) DEFAULT NULL, checkout_s_country_iso VARCHAR(10) NOT NULL, checkout_s_country VARCHAR(100) NOT NULL, checkout_s_street_address VARCHAR(500) NOT NULL, checkout_s_address VARCHAR(255) DEFAULT NULL, checkout_s_city VARCHAR(100) NOT NULL, checkout_s_state VARCHAR(100) NOT NULL, checkout_s_postcode VARCHAR(50) NOT NULL, checkout_s_email VARCHAR(100) NOT NULL, checkout_s_phone VARCHAR(100) NOT NULL, checkout_comment VARCHAR(255) DEFAULT NULL, different_address BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_6793503b9395c3f3 ON mia_order (customer_id)');
        $this->addSql('CREATE TABLE items_guide_number (id INT NOT NULL, guide_number_id INT NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_bfb5e8ba7d263ef6 ON items_guide_number (guide_number_id)');
        $this->addSql('CREATE TABLE mia_customer_coupon_discount (id BIGINT NOT NULL, customer_id BIGINT NOT NULL, percent BOOLEAN NOT NULL, discount DOUBLE PRECISION NOT NULL, coupon VARCHAR(255) NOT NULL, date_apply TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, applied BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_eec086f79395c3f3 ON mia_customer_coupon_discount (customer_id)');
        $this->addSql('CREATE TABLE communication_states_between_platforms (id INT NOT NULL, name VARCHAR(20) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE customers_types_roles (id INT NOT NULL, role VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE guide_numbers (id INT NOT NULL, number_order_id INT NOT NULL, number VARCHAR(255) NOT NULL, courier_name VARCHAR(255) NOT NULL, courier_id INT NOT NULL, lb DOUBLE PRECISION DEFAULT NULL, height DOUBLE PRECISION DEFAULT NULL, width DOUBLE PRECISION DEFAULT NULL, depth DOUBLE PRECISION DEFAULT NULL, service_id INT DEFAULT NULL, service_name VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_438850d1d8d98c7 ON guide_numbers (number_order_id)');
        $this->addSql('CREATE TABLE mia_order_items (id BIGINT NOT NULL, order_id BIGINT NOT NULL, pid BIGINT NOT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, image VARCHAR(500) DEFAULT NULL, price DOUBLE PRECISION NOT NULL, quantity INT NOT NULL, total DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_dfe5ae9b8d9f6d38 ON mia_order_items (order_id)');
        $this->addSql('CREATE TABLE customer_addresses (id INT NOT NULL, registration_type_id INT DEFAULT NULL, registration_user_id BIGINT DEFAULT NULL, country_id INT NOT NULL, state_id INT DEFAULT NULL, city_id INT DEFAULT NULL, street VARCHAR(255) NOT NULL, number_street VARCHAR(255) NOT NULL, floor VARCHAR(10) NOT NULL, department VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, additional_info VARCHAR(255) DEFAULT NULL, home_address BOOLEAN NOT NULL, billing_address BOOLEAN NOT NULL, registration_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, active BOOLEAN DEFAULT true NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_c4378d0cf92f3e70 ON customer_addresses (country_id)');
        $this->addSql('CREATE INDEX idx_c4378d0ce71f8633 ON customer_addresses (registration_user_id)');
        $this->addSql('CREATE INDEX idx_c4378d0c8bac62af ON customer_addresses (city_id)');
        $this->addSql('CREATE INDEX idx_c4378d0c853dd935 ON customer_addresses (registration_type_id)');
        $this->addSql('CREATE INDEX idx_c4378d0c5d83cc1 ON customer_addresses (state_id)');
        $this->addSql('CREATE TABLE mia_coupon_discount (id BIGINT NOT NULL, percent BOOLEAN NOT NULL, value DOUBLE PRECISION NOT NULL, number_of_uses INT NOT NULL, nro VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE recipients (id INT NOT NULL, country_id INT NOT NULL, state_id INT NOT NULL, city_id INT NOT NULL, name VARCHAR(255) NOT NULL, identity_type VARCHAR(50) NOT NULL, identity_number VARCHAR(255) NOT NULL, address TEXT NOT NULL, zip_code VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, additional_info TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_146632c48bac62af ON recipients (city_id)');
        $this->addSql('CREATE INDEX idx_146632c45d83cc1 ON recipients (state_id)');
        $this->addSql('CREATE INDEX idx_146632c4f92f3e70 ON recipients (country_id)');
        $this->addSql('CREATE TABLE payments_transactions_codes (id INT NOT NULL, order_number_id INT NOT NULL, payment_transaction_code TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_eae13e988c26a5e8 ON payments_transactions_codes (order_number_id)');
        $this->addSql('ALTER TABLE product_discount ADD CONSTRAINT fk_2a50de997d182d95 FOREIGN KEY (created_by_user_id) REFERENCES mia_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_order ADD CONSTRAINT fk_6793503b9395c3f3 FOREIGN KEY (customer_id) REFERENCES mia_customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE items_guide_number ADD CONSTRAINT fk_bfb5e8ba7d263ef6 FOREIGN KEY (guide_number_id) REFERENCES guide_numbers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_customer_coupon_discount ADD CONSTRAINT fk_eec086f79395c3f3 FOREIGN KEY (customer_id) REFERENCES mia_customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE guide_numbers ADD CONSTRAINT fk_438850d1d8d98c7 FOREIGN KEY (number_order_id) REFERENCES orders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_order_items ADD CONSTRAINT fk_dfe5ae9b8d9f6d38 FOREIGN KEY (order_id) REFERENCES mia_order (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE customer_addresses ADD CONSTRAINT fk_c4378d0c5d83cc1 FOREIGN KEY (state_id) REFERENCES states (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE customer_addresses ADD CONSTRAINT fk_c4378d0c853dd935 FOREIGN KEY (registration_type_id) REFERENCES registration_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE customer_addresses ADD CONSTRAINT fk_c4378d0c8bac62af FOREIGN KEY (city_id) REFERENCES cities (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE customer_addresses ADD CONSTRAINT fk_c4378d0ce71f8633 FOREIGN KEY (registration_user_id) REFERENCES mia_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE customer_addresses ADD CONSTRAINT fk_c4378d0cf92f3e70 FOREIGN KEY (country_id) REFERENCES countries (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recipients ADD CONSTRAINT fk_146632c4f92f3e70 FOREIGN KEY (country_id) REFERENCES countries (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recipients ADD CONSTRAINT fk_146632c45d83cc1 FOREIGN KEY (state_id) REFERENCES states (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recipients ADD CONSTRAINT fk_146632c48bac62af FOREIGN KEY (city_id) REFERENCES cities (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payments_transactions_codes ADD CONSTRAINT fk_eae13e988c26a5e8 FOREIGN KEY (order_number_id) REFERENCES orders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders_products ADD product_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE orders_products ADD product_discount_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders_products ADD CONSTRAINT fk_749c879c4584665a FOREIGN KEY (product_id) REFERENCES mia_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders_products ADD CONSTRAINT fk_749c879ce053ff00 FOREIGN KEY (product_discount_id) REFERENCES product_discount (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_749c879ce053ff00 ON orders_products (product_discount_id)');
        $this->addSql('CREATE INDEX idx_749c879c4584665a ON orders_products (product_id)');
        $this->addSql('ALTER TABLE orders ADD customer_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE orders ADD customer_phone_code_id INT NOT NULL');
        $this->addSql('ALTER TABLE orders ADD bill_address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD biil_country_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD receiver_country_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD status_sent_crm_id INT NOT NULL');
        $this->addSql('ALTER TABLE orders ADD shipping_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD recipient_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD cel_phone_customer VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE orders ADD international_shipping BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD shipping BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD inventory_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD error_message_crm VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD attempts_send_crm SMALLINT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE orders ADD sales_id_3pl INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders RENAME COLUMN code_area_phone_customer TO customer_identity_type');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT fk_e52ffdee23048a57 FOREIGN KEY (shipping_type_id) REFERENCES shipping_types (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT fk_e52ffdee4a3d0927 FOREIGN KEY (biil_country_id) REFERENCES countries (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT fk_e52ffdee5b8a2b31 FOREIGN KEY (bill_address_id) REFERENCES customer_addresses (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT fk_e52ffdeeb4b9e7e FOREIGN KEY (status_sent_crm_id) REFERENCES communication_states_between_platforms (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT fk_e52ffdeeba8b38b9 FOREIGN KEY (receiver_country_id) REFERENCES countries (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT fk_e52ffdeed3fd0226 FOREIGN KEY (customer_phone_code_id) REFERENCES countries (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT fk_e52ffdeed991282d FOREIGN KEY (customer_type_id) REFERENCES customers_types_roles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT fk_e52ffdeee92f8f78 FOREIGN KEY (recipient_id) REFERENCES recipients (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_e52ffdeee92f8f78 ON orders (recipient_id)');
        $this->addSql('CREATE INDEX idx_e52ffdeed991282d ON orders (customer_type_id)');
        $this->addSql('CREATE INDEX idx_e52ffdeed3fd0226 ON orders (customer_phone_code_id)');
        $this->addSql('CREATE INDEX idx_e52ffdeeba8b38b9 ON orders (receiver_country_id)');
        $this->addSql('CREATE INDEX idx_e52ffdeeb4b9e7e ON orders (status_sent_crm_id)');
        $this->addSql('CREATE INDEX idx_e52ffdee5b8a2b31 ON orders (bill_address_id)');
        $this->addSql('CREATE INDEX idx_e52ffdee4a3d0927 ON orders (biil_country_id)');
        $this->addSql('CREATE INDEX idx_e52ffdee23048a57 ON orders (shipping_type_id)');
    }
}
