<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230313113858 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category CHANGE title title VARCHAR(500) NOT NULL');
        $this->addSql('ALTER TABLE question ADD parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E727ACA70 FOREIGN KEY (parent_id) REFERENCES question (id)');
        $this->addSql('CREATE INDEX IDX_B6F7494E727ACA70 ON question (parent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E727ACA70');
        $this->addSql('DROP INDEX IDX_B6F7494E727ACA70 ON question');
        $this->addSql('ALTER TABLE question DROP parent_id');
        $this->addSql('ALTER TABLE category CHANGE title title VARCHAR(255) NOT NULL');
    }
}
