<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240516032443 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE debit_credit_notes_files_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE payments_received_files_id_seq CASCADE');
        $this->addSql('ALTER TABLE debit_credit_notes_files DROP CONSTRAINT fk_7044e413d8d98c7');
        $this->addSql('ALTER TABLE payments_received_files DROP CONSTRAINT fk_a8b3a4d38c26a5e8');
        $this->addSql('DROP TABLE debit_credit_notes_files');
        $this->addSql('DROP TABLE payments_received_files');
        $this->addSql("INSERT INTO status_order_type (id,name) values (1,'Abierta'), (2,'Confirmada'),(3,'Cancelada')");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE debit_credit_notes_files_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE payments_received_files_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE debit_credit_notes_files (id INT NOT NULL, number_order_id INT NOT NULL, debit_credit_note_file TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_7044e413d8d98c7 ON debit_credit_notes_files (number_order_id)');
        $this->addSql('CREATE TABLE payments_received_files (id INT NOT NULL, order_number_id INT NOT NULL, payment_received_file TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_a8b3a4d38c26a5e8 ON payments_received_files (order_number_id)');
        $this->addSql('ALTER TABLE debit_credit_notes_files ADD CONSTRAINT fk_7044e413d8d98c7 FOREIGN KEY (number_order_id) REFERENCES orders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payments_received_files ADD CONSTRAINT fk_a8b3a4d38c26a5e8 FOREIGN KEY (order_number_id) REFERENCES orders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
