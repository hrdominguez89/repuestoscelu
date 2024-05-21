<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240521093551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE email_types SET name='BIENVENIDO A REPUESTOSCELU', template_name='welcome' WHERE id=1");
        $this->addSql("UPDATE email_types SET name='RECIBIMOS TU ORDEN', template_name='new.order.customer' WHERE id=2");
        $this->addSql("UPDATE email_types SET name='INGRESO DE NUEVA ORDEN', template_name='new.order.sale.point' WHERE id=6");
        $this->addSql("INSERT INTO email_types (id,name,template_name) values (9,'TU ORDEN SE ENCUENTRA CONFIRMADA','confirmed.order.customer'),(10,'CANCELACIÓN DE ORDEN','canceled.order.customer')");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
    }
}
