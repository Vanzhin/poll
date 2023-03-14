<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230313121613 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E727ACA70');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E727ACA70 FOREIGN KEY (parent_id) REFERENCES question (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E727ACA70');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E727ACA70 FOREIGN KEY (parent_id) REFERENCES question (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
