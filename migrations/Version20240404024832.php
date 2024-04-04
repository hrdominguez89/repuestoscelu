<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240404024832 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT fk_e52ffdeedc058279');
        $this->addSql('DROP SEQUENCE payment_type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE status_type_transaction_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE transactions_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE recipients_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE recipients (id INT NOT NULL, customer_id BIGINT NOT NULL, country_id INT NOT NULL, state_id INT NOT NULL, city_id INT NOT NULL, name VARCHAR(255) NOT NULL, identity_type VARCHAR(50) NOT NULL, identity_number VARCHAR(255) NOT NULL, address TEXT NOT NULL, zip_code VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, additional_info TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_146632C49395C3F3 ON recipients (customer_id)');
        $this->addSql('CREATE INDEX IDX_146632C4F92F3E70 ON recipients (country_id)');
        $this->addSql('CREATE INDEX IDX_146632C45D83CC1 ON recipients (state_id)');
        $this->addSql('CREATE INDEX IDX_146632C48BAC62AF ON recipients (city_id)');
        $this->addSql('ALTER TABLE recipients ADD CONSTRAINT FK_146632C49395C3F3 FOREIGN KEY (customer_id) REFERENCES mia_customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recipients ADD CONSTRAINT FK_146632C4F92F3E70 FOREIGN KEY (country_id) REFERENCES countries (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recipients ADD CONSTRAINT FK_146632C45D83CC1 FOREIGN KEY (state_id) REFERENCES states (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recipients ADD CONSTRAINT FK_146632C48BAC62AF FOREIGN KEY (city_id) REFERENCES cities (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transactions DROP CONSTRAINT fk_eaa81a4c6bf700bd');
        $this->addSql('ALTER TABLE transactions DROP CONSTRAINT fk_eaa81a4cd8d98c7');
        $this->addSql('DROP TABLE payment_type');
        $this->addSql('DROP TABLE transactions');
        $this->addSql('DROP TABLE status_type_transaction');
        $this->addSql('ALTER TABLE customer_addresses DROP name');
        $this->addSql('ALTER TABLE customer_addresses DROP identity_type');
        $this->addSql('ALTER TABLE customer_addresses DROP identity_number');
        $this->addSql('ALTER TABLE customer_addresses DROP phone');
        $this->addSql('ALTER TABLE customer_addresses DROP email');
        $this->addSql('ALTER TABLE customer_addresses DROP recipe_address');
        $this->addSql('ALTER TABLE customer_addresses ALTER number_street SET NOT NULL');
        $this->addSql('ALTER TABLE customer_addresses ALTER floor SET NOT NULL');
        $this->addSql('ALTER TABLE customer_addresses ALTER department SET NOT NULL');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT fk_e52ffdeeb613a9bc');
        $this->addSql('DROP INDEX idx_e52ffdeedc058279');
        $this->addSql('DROP INDEX idx_e52ffdeeb613a9bc');
        $this->addSql('ALTER TABLE orders ADD recipient_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders DROP receiver_address_id');
        $this->addSql('ALTER TABLE orders DROP payment_type_id');
        $this->addSql('ALTER TABLE orders DROP bill_name');
        $this->addSql('ALTER TABLE orders DROP bill_identity_type');
        $this->addSql('ALTER TABLE orders DROP bill_identity_number');
        $this->addSql('ALTER TABLE orders DROP proforma_bill_file');
        $this->addSql('ALTER TABLE orders DROP fiscal_invoice_required');
        $this->addSql('ALTER TABLE orders RENAME COLUMN receiver_address_order TO receiver_address');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEE92F8F78 FOREIGN KEY (recipient_id) REFERENCES recipients (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E52FFDEEE92F8F78 ON orders (recipient_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEEE92F8F78');
        $this->addSql('DROP SEQUENCE recipients_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE payment_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE status_type_transaction_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE transactions_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE payment_type (id INT NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) DEFAULT NULL, active BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE transactions (id INT NOT NULL, number_order_id INT NOT NULL, status_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, tax DOUBLE PRECISION NOT NULL, amount DOUBLE PRECISION NOT NULL, error_message TEXT DEFAULT NULL, session VARCHAR(255) DEFAULT NULL, session_key VARCHAR(255) DEFAULT NULL, authorization_code VARCHAR(255) DEFAULT NULL, tx_token VARCHAR(255) DEFAULT NULL, response_code VARCHAR(255) DEFAULT NULL, creditcard_number VARCHAR(255) DEFAULT NULL, retrival_reference_number VARCHAR(255) DEFAULT NULL, remote_response_code VARCHAR(255) DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_eaa81a4cd8d98c7 ON transactions (number_order_id)');
        $this->addSql('CREATE INDEX idx_eaa81a4c6bf700bd ON transactions (status_id)');
        $this->addSql('CREATE TABLE status_type_transaction (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT fk_eaa81a4c6bf700bd FOREIGN KEY (status_id) REFERENCES status_type_transaction (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT fk_eaa81a4cd8d98c7 FOREIGN KEY (number_order_id) REFERENCES orders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recipients DROP CONSTRAINT FK_146632C49395C3F3');
        $this->addSql('ALTER TABLE recipients DROP CONSTRAINT FK_146632C4F92F3E70');
        $this->addSql('ALTER TABLE recipients DROP CONSTRAINT FK_146632C45D83CC1');
        $this->addSql('ALTER TABLE recipients DROP CONSTRAINT FK_146632C48BAC62AF');
        $this->addSql('DROP TABLE recipients');
        $this->addSql('ALTER TABLE customer_addresses ADD name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE customer_addresses ADD identity_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE customer_addresses ADD identity_number VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE customer_addresses ADD phone VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE customer_addresses ADD email VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE customer_addresses ADD recipe_address BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE customer_addresses ALTER number_street DROP NOT NULL');
        $this->addSql('ALTER TABLE customer_addresses ALTER floor DROP NOT NULL');
        $this->addSql('ALTER TABLE customer_addresses ALTER department DROP NOT NULL');
        $this->addSql('DROP INDEX IDX_E52FFDEEE92F8F78');
        $this->addSql('ALTER TABLE orders ADD payment_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD bill_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD bill_identity_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD bill_identity_number VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD proforma_bill_file VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD fiscal_invoice_required BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE orders RENAME COLUMN recipient_id TO receiver_address_id');
        $this->addSql('ALTER TABLE orders RENAME COLUMN receiver_address TO receiver_address_order');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT fk_e52ffdeeb613a9bc FOREIGN KEY (receiver_address_id) REFERENCES customer_addresses (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT fk_e52ffdeedc058279 FOREIGN KEY (payment_type_id) REFERENCES payment_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_e52ffdeedc058279 ON orders (payment_type_id)');
        $this->addSql('CREATE INDEX idx_e52ffdeeb613a9bc ON orders (receiver_address_id)');
    }
}
