<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230912133507 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE protocol_user (protocol_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_889D293ECCD59258 (protocol_id), INDEX IDX_889D293EA76ED395 (user_id), PRIMARY KEY(protocol_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE protocol_user ADD CONSTRAINT FK_889D293ECCD59258 FOREIGN KEY (protocol_id) REFERENCES protocol (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE protocol_user ADD CONSTRAINT FK_889D293EA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE protocol_user DROP FOREIGN KEY FK_889D293ECCD59258');
        $this->addSql('ALTER TABLE protocol_user DROP FOREIGN KEY FK_889D293EA76ED395');
        $this->addSql('DROP TABLE protocol_user');
    }
}
