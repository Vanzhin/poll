<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230815133858 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `group` DROP FOREIGN KEY FK_6DC044C5CCD59258');
        $this->addSql('DROP INDEX UNIQ_6DC044C5CCD59258 ON `group`');
        $this->addSql('ALTER TABLE `group` DROP protocol_id');
        $this->addSql('ALTER TABLE protocol ADD groups_id INT NOT NULL, CHANGE order_date order_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE protocol ADD CONSTRAINT FK_C8C0BC4CF373DCF FOREIGN KEY (groups_id) REFERENCES `group` (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C8C0BC4CF373DCF ON protocol (groups_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `group` ADD protocol_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `group` ADD CONSTRAINT FK_6DC044C5CCD59258 FOREIGN KEY (protocol_id) REFERENCES protocol (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6DC044C5CCD59258 ON `group` (protocol_id)');
        $this->addSql('ALTER TABLE protocol DROP FOREIGN KEY FK_C8C0BC4CF373DCF');
        $this->addSql('DROP INDEX UNIQ_C8C0BC4CF373DCF ON protocol');
        $this->addSql('ALTER TABLE protocol DROP groups_id, CHANGE order_date order_date DATE NOT NULL');
    }
}
