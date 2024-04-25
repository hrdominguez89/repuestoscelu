<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240425205023 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT fk_540dea373917014');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT fk_540dea375cc5db90');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT fk_540dea376c8c2577');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT fk_540dea377975b7e7');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT fk_540dea377ada1fb5');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT fk_540dea3785e0bc94');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT fk_540dea3798003202');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT fk_540dea37b092b37b');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT fk_540dea37ccc80cb3');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT fk_540dea37e6f67fe9');
        $this->addSql('DROP SEQUENCE mia_specification_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE specification_types_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE colors_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE cpu_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE gpu_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE memory_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE models_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE os_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE screen_resolution_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE screen_size_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE storage_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE colors (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE cpu (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE gpu (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE memory (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE models (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE os (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE screen_resolution (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE screen_size (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE storage (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');

        $this->addSql('INSERT INTO screen_resolution (id,name) SELECT id, name FROM mia_specification WHERE specification_type_id=1');
        $this->addSql('INSERT INTO screen_size (id,name) SELECT id, name FROM mia_specification WHERE specification_type_id=2');
        $this->addSql('INSERT INTO cpu (id,name) SELECT id, name FROM mia_specification WHERE specification_type_id=3');
        $this->addSql('INSERT INTO gpu (id,name) SELECT id, name FROM mia_specification WHERE specification_type_id=4');
        $this->addSql('INSERT INTO memory (id,name) SELECT id, name FROM mia_specification WHERE specification_type_id=5');
        $this->addSql('INSERT INTO storage (id,name) SELECT id, name FROM mia_specification WHERE specification_type_id=6');
        $this->addSql('INSERT INTO os (id,name) SELECT id, name FROM mia_specification WHERE specification_type_id=7');
        $this->addSql('INSERT INTO colors (id,name) SELECT id, name FROM mia_specification WHERE specification_type_id=9');
        $this->addSql('INSERT INTO models (id,name) SELECT id, name FROM mia_specification WHERE specification_type_id=10');

        $this->addSql('ALTER TABLE mia_specification DROP CONSTRAINT fk_c79f3b8086f60d4e');
        $this->addSql('DROP TABLE specification_types');
        $this->addSql('DROP TABLE mia_specification');
        $this->addSql('DROP INDEX idx_540dea37b092b37b');
        $this->addSql('DROP INDEX idx_540dea3798003202');
        $this->addSql('DROP INDEX idx_540dea3785e0bc94');
        $this->addSql('DROP INDEX idx_540dea377ada1fb5');
        $this->addSql('DROP INDEX idx_540dea377975b7e7');
        $this->addSql('DROP INDEX idx_540dea373917014');
        $this->addSql('ALTER TABLE mia_product ADD colors_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD models_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD c_pu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD g_pu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD o_s_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD long_description TEXT DEFAULT NULL');

        $this->addSql('UPDATE mia_product set models_id=model_id');
        $this->addSql('UPDATE mia_product set colors_id=color_id');
        $this->addSql('UPDATE mia_product set c_pu_id=cpu_id');
        $this->addSql('UPDATE mia_product set g_pu_id=gpu_id');
        $this->addSql('UPDATE mia_product set o_s_id=op_sys_id');

        $this->addSql('ALTER TABLE mia_product DROP model_id');
        $this->addSql('ALTER TABLE mia_product DROP color_id');
        $this->addSql('ALTER TABLE mia_product DROP cpu_id');
        $this->addSql('ALTER TABLE mia_product DROP gpu_id');
        $this->addSql('ALTER TABLE mia_product DROP op_sys_id');
        $this->addSql('ALTER TABLE mia_product DROP conditium_id');
        $this->addSql('ALTER TABLE mia_product DROP description_es');
        $this->addSql('ALTER TABLE mia_product DROP long_description_es');
        $this->addSql('ALTER TABLE mia_product ALTER screen_resolution_id TYPE INT');
        $this->addSql('ALTER TABLE mia_product ALTER screen_size_id TYPE INT');
        $this->addSql('ALTER TABLE mia_product ALTER memory_id TYPE INT');
        $this->addSql('ALTER TABLE mia_product ALTER storage_id TYPE INT');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA375C002039 FOREIGN KEY (colors_id) REFERENCES colors (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA37D966BF49 FOREIGN KEY (models_id) REFERENCES models (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA37E6D99F67 FOREIGN KEY (c_pu_id) REFERENCES cpu (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA371296BB74 FOREIGN KEY (g_pu_id) REFERENCES gpu (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA3712A95766 FOREIGN KEY (o_s_id) REFERENCES os (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA375CC5DB90 FOREIGN KEY (storage_id) REFERENCES storage (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA376C8C2577 FOREIGN KEY (screen_resolution_id) REFERENCES screen_resolution (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA37CCC80CB3 FOREIGN KEY (memory_id) REFERENCES memory (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA37E6F67FE9 FOREIGN KEY (screen_size_id) REFERENCES screen_size (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_540DEA375C002039 ON mia_product (colors_id)');
        $this->addSql('CREATE INDEX IDX_540DEA37D966BF49 ON mia_product (models_id)');
        $this->addSql('CREATE INDEX IDX_540DEA37E6D99F67 ON mia_product (c_pu_id)');
        $this->addSql('CREATE INDEX IDX_540DEA371296BB74 ON mia_product (g_pu_id)');
        $this->addSql('CREATE INDEX IDX_540DEA3712A95766 ON mia_product (o_s_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA375C002039');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA37E6D99F67');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA371296BB74');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA37CCC80CB3');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA37D966BF49');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA3712A95766');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA376C8C2577');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA37E6F67FE9');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA375CC5DB90');
        $this->addSql('DROP SEQUENCE colors_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE cpu_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE gpu_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE memory_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE models_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE os_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE screen_resolution_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE screen_size_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE storage_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE mia_specification_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE specification_types_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE specification_types (id INT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_e7731a575e237e06 ON specification_types (name)');
        $this->addSql('CREATE TABLE mia_specification (id BIGINT NOT NULL, specification_type_id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, active BOOLEAN NOT NULL, color_hexadecimal VARCHAR(7) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_c79f3b8086f60d4e ON mia_specification (specification_type_id)');
        $this->addSql('ALTER TABLE mia_specification ADD CONSTRAINT fk_c79f3b8086f60d4e FOREIGN KEY (specification_type_id) REFERENCES specification_types (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE colors');
        $this->addSql('DROP TABLE cpu');
        $this->addSql('DROP TABLE gpu');
        $this->addSql('DROP TABLE memory');
        $this->addSql('DROP TABLE models');
        $this->addSql('DROP TABLE os');
        $this->addSql('DROP TABLE screen_resolution');
        $this->addSql('DROP TABLE screen_size');
        $this->addSql('DROP TABLE storage');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT fk_540dea37e6f67fe9');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT fk_540dea376c8c2577');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT fk_540dea37ccc80cb3');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT fk_540dea375cc5db90');
        $this->addSql('DROP INDEX IDX_540DEA375C002039');
        $this->addSql('DROP INDEX IDX_540DEA37D966BF49');
        $this->addSql('DROP INDEX IDX_540DEA37E6D99F67');
        $this->addSql('DROP INDEX IDX_540DEA371296BB74');
        $this->addSql('DROP INDEX IDX_540DEA3712A95766');
        $this->addSql('ALTER TABLE mia_product ADD model_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE mia_product ADD color_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD cpu_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD gpu_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD op_sys_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD conditium_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE mia_product ADD description_es TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD long_description_es TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product DROP colors_id');
        $this->addSql('ALTER TABLE mia_product DROP models_id');
        $this->addSql('ALTER TABLE mia_product DROP c_pu_id');
        $this->addSql('ALTER TABLE mia_product DROP g_pu_id');
        $this->addSql('ALTER TABLE mia_product DROP o_s_id');
        $this->addSql('ALTER TABLE mia_product DROP description');
        $this->addSql('ALTER TABLE mia_product DROP long_description');
        $this->addSql('ALTER TABLE mia_product ALTER screen_size_id TYPE BIGINT');
        $this->addSql('ALTER TABLE mia_product ALTER screen_resolution_id TYPE BIGINT');
        $this->addSql('ALTER TABLE mia_product ALTER memory_id TYPE BIGINT');
        $this->addSql('ALTER TABLE mia_product ALTER storage_id TYPE BIGINT');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT fk_540dea373917014 FOREIGN KEY (cpu_id) REFERENCES mia_specification (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT fk_540dea377975b7e7 FOREIGN KEY (model_id) REFERENCES mia_specification (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT fk_540dea377ada1fb5 FOREIGN KEY (color_id) REFERENCES mia_specification (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT fk_540dea3785e0bc94 FOREIGN KEY (op_sys_id) REFERENCES mia_specification (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT fk_540dea3798003202 FOREIGN KEY (gpu_id) REFERENCES mia_specification (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT fk_540dea37b092b37b FOREIGN KEY (conditium_id) REFERENCES mia_specification (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT fk_540dea37e6f67fe9 FOREIGN KEY (screen_size_id) REFERENCES mia_specification (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT fk_540dea376c8c2577 FOREIGN KEY (screen_resolution_id) REFERENCES mia_specification (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT fk_540dea37ccc80cb3 FOREIGN KEY (memory_id) REFERENCES mia_specification (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT fk_540dea375cc5db90 FOREIGN KEY (storage_id) REFERENCES mia_specification (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_540dea37b092b37b ON mia_product (conditium_id)');
        $this->addSql('CREATE INDEX idx_540dea3798003202 ON mia_product (gpu_id)');
        $this->addSql('CREATE INDEX idx_540dea3785e0bc94 ON mia_product (op_sys_id)');
        $this->addSql('CREATE INDEX idx_540dea377ada1fb5 ON mia_product (color_id)');
        $this->addSql('CREATE INDEX idx_540dea377975b7e7 ON mia_product (model_id)');
        $this->addSql('CREATE INDEX idx_540dea373917014 ON mia_product (cpu_id)');
    }
}
