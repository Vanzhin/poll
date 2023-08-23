<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230813093651 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE protocol (id INT AUTO_INCREMENT NOT NULL, commission_id INT NOT NULL, order_number VARCHAR(10) NOT NULL, order_date DATE NOT NULL, check_reason LONGTEXT NOT NULL, INDEX IDX_C8C0BC4C202D1EB2 (commission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE protocol ADD CONSTRAINT FK_C8C0BC4C202D1EB2 FOREIGN KEY (commission_id) REFERENCES commission (id)');
        $this->addSql('ALTER TABLE `group` ADD protocol_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `group` ADD CONSTRAINT FK_6DC044C5CCD59258 FOREIGN KEY (protocol_id) REFERENCES protocol (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6DC044C5CCD59258 ON `group` (protocol_id)');
        $this->addSql('ALTER TABLE `group` RENAME INDEX idx_6dc044c5b03a8386 TO IDX_6DC044C57E3C61F9');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `group` DROP FOREIGN KEY FK_6DC044C5CCD59258');
        $this->addSql('ALTER TABLE protocol DROP FOREIGN KEY FK_C8C0BC4C202D1EB2');
        $this->addSql('DROP TABLE protocol');
        $this->addSql('DROP INDEX UNIQ_6DC044C5CCD59258 ON `group`');
        $this->addSql('ALTER TABLE `group` DROP protocol_id');
        $this->addSql('ALTER TABLE `group` RENAME INDEX idx_6dc044c57e3c61f9 TO IDX_6DC044C5B03A8386');
    }
}
