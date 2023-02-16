<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230213100323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE variant ADD question_id INT NOT NULL');
        $this->addSql('ALTER TABLE variant ADD CONSTRAINT FK_F143BFAD1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('CREATE INDEX IDX_F143BFAD1E27F6BF ON variant (question_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE variant DROP FOREIGN KEY FK_F143BFAD1E27F6BF');
        $this->addSql('DROP INDEX IDX_F143BFAD1E27F6BF ON variant');
        $this->addSql('ALTER TABLE variant DROP question_id');
    }
}
