<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240425233942 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT fk_540dea37e6d99f67');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT fk_540dea371296bb74');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT fk_540dea3712a95766');
        $this->addSql('DROP INDEX idx_540dea3712a95766');
        $this->addSql('DROP INDEX idx_540dea371296bb74');
        $this->addSql('DROP INDEX idx_540dea37e6d99f67');
        $this->addSql('ALTER TABLE mia_product ADD cpu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD gpu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD os_id INT DEFAULT NULL');

        $this->addSql('UPDATE mia_product set cpu_id=c_pu_id');
        $this->addSql('UPDATE mia_product set gpu_id=g_pu_id');
        $this->addSql('UPDATE mia_product set os_id=o_s_id');

        $this->addSql('ALTER TABLE mia_product DROP c_pu_id');
        $this->addSql('ALTER TABLE mia_product DROP g_pu_id');
        $this->addSql('ALTER TABLE mia_product DROP o_s_id');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA373917014 FOREIGN KEY (cpu_id) REFERENCES cpu (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA3798003202 FOREIGN KEY (gpu_id) REFERENCES gpu (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA373DCA04D1 FOREIGN KEY (os_id) REFERENCES os (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_540DEA373917014 ON mia_product (cpu_id)');
        $this->addSql('CREATE INDEX IDX_540DEA3798003202 ON mia_product (gpu_id)');
        $this->addSql('CREATE INDEX IDX_540DEA373DCA04D1 ON mia_product (os_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA373917014');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA3798003202');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA373DCA04D1');
        $this->addSql('DROP INDEX IDX_540DEA373917014');
        $this->addSql('DROP INDEX IDX_540DEA3798003202');
        $this->addSql('DROP INDEX IDX_540DEA373DCA04D1');
        $this->addSql('ALTER TABLE mia_product ADD c_pu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD g_pu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD o_s_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product DROP cpu_id');
        $this->addSql('ALTER TABLE mia_product DROP gpu_id');
        $this->addSql('ALTER TABLE mia_product DROP os_id');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT fk_540dea37e6d99f67 FOREIGN KEY (c_pu_id) REFERENCES cpu (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT fk_540dea371296bb74 FOREIGN KEY (g_pu_id) REFERENCES gpu (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT fk_540dea3712a95766 FOREIGN KEY (o_s_id) REFERENCES os (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_540dea3712a95766 ON mia_product (o_s_id)');
        $this->addSql('CREATE INDEX idx_540dea371296bb74 ON mia_product (g_pu_id)');
        $this->addSql('CREATE INDEX idx_540dea37e6d99f67 ON mia_product (c_pu_id)');
    }
}
