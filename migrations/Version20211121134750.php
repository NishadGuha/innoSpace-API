<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211121134750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE device_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE house_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE neighborhood_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE usage_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE device (id INT NOT NULL, house_id INT DEFAULT NULL, make VARCHAR(255) DEFAULT NULL, wattage VARCHAR(255) DEFAULT NULL, voltage VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_92FB68E6BB74515 ON device (house_id)');
        $this->addSql('CREATE TABLE house (id INT NOT NULL, neighborhood_id INT DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) NOT NULL, house_number VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_67D5399D803BB24B ON house (neighborhood_id)');
        $this->addSql('CREATE TABLE neighborhood (id INT NOT NULL, direction VARCHAR(255) DEFAULT NULL, street VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE usage (id INT NOT NULL, house_id INT NOT NULL, device_id INT DEFAULT NULL, time_created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, consumption VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D0EB5E706BB74515 ON usage (house_id)');
        $this->addSql('CREATE INDEX IDX_D0EB5E7094A4C7D4 ON usage (device_id)');
        $this->addSql('ALTER TABLE device ADD CONSTRAINT FK_92FB68E6BB74515 FOREIGN KEY (house_id) REFERENCES house (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE house ADD CONSTRAINT FK_67D5399D803BB24B FOREIGN KEY (neighborhood_id) REFERENCES neighborhood (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE usage ADD CONSTRAINT FK_D0EB5E706BB74515 FOREIGN KEY (house_id) REFERENCES house (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE usage ADD CONSTRAINT FK_D0EB5E7094A4C7D4 FOREIGN KEY (device_id) REFERENCES device (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE usage DROP CONSTRAINT FK_D0EB5E7094A4C7D4');
        $this->addSql('ALTER TABLE device DROP CONSTRAINT FK_92FB68E6BB74515');
        $this->addSql('ALTER TABLE usage DROP CONSTRAINT FK_D0EB5E706BB74515');
        $this->addSql('ALTER TABLE house DROP CONSTRAINT FK_67D5399D803BB24B');
        $this->addSql('DROP SEQUENCE device_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE house_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE neighborhood_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE usage_id_seq CASCADE');
        $this->addSql('DROP TABLE device');
        $this->addSql('DROP TABLE house');
        $this->addSql('DROP TABLE neighborhood');
        $this->addSql('DROP TABLE usage');
    }
}
