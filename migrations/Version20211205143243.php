<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211205143243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE device_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE house_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE device_type (id INT NOT NULL, type VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE house_type (id INT NOT NULL, type VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE device ADD device_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE device ADD priority_rating INT DEFAULT NULL');
        $this->addSql('ALTER TABLE device ADD plugged_in BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE device ADD CONSTRAINT FK_92FB68E4FFA550E FOREIGN KEY (device_type_id) REFERENCES device_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_92FB68E4FFA550E ON device (device_type_id)');
        $this->addSql('ALTER TABLE house ADD house_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE house ADD occupants INT DEFAULT NULL');
        $this->addSql('ALTER TABLE house ADD CONSTRAINT FK_67D5399D519B0A8E FOREIGN KEY (house_type_id) REFERENCES house_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_67D5399D519B0A8E ON house (house_type_id)');
        $this->addSql('ALTER TABLE usage ADD duration VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE device DROP CONSTRAINT FK_92FB68E4FFA550E');
        $this->addSql('ALTER TABLE house DROP CONSTRAINT FK_67D5399D519B0A8E');
        $this->addSql('DROP SEQUENCE device_type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE house_type_id_seq CASCADE');
        $this->addSql('DROP TABLE device_type');
        $this->addSql('DROP TABLE house_type');
        $this->addSql('DROP INDEX IDX_67D5399D519B0A8E');
        $this->addSql('ALTER TABLE house DROP house_type_id');
        $this->addSql('ALTER TABLE house DROP occupants');
        $this->addSql('DROP INDEX IDX_92FB68E4FFA550E');
        $this->addSql('ALTER TABLE device DROP device_type_id');
        $this->addSql('ALTER TABLE device DROP priority_rating');
        $this->addSql('ALTER TABLE device DROP plugged_in');
        $this->addSql('ALTER TABLE usage DROP duration');
    }
}
