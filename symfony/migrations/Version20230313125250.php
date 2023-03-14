<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230313125250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE variant_question (variant_id INT NOT NULL, question_id INT NOT NULL, INDEX IDX_292708C63B69A9AF (variant_id), INDEX IDX_292708C61E27F6BF (question_id), PRIMARY KEY(variant_id, question_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE variant_question ADD CONSTRAINT FK_292708C63B69A9AF FOREIGN KEY (variant_id) REFERENCES variant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE variant_question ADD CONSTRAINT FK_292708C61E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE variant DROP FOREIGN KEY FK_F143BFAD1E27F6BF');
        $this->addSql('DROP INDEX variant_question_idx ON variant');
        $this->addSql('DROP INDEX IDX_F143BFAD1E27F6BF ON variant');
        $this->addSql('ALTER TABLE variant DROP question_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE variant_question DROP FOREIGN KEY FK_292708C63B69A9AF');
        $this->addSql('ALTER TABLE variant_question DROP FOREIGN KEY FK_292708C61E27F6BF');
        $this->addSql('DROP TABLE variant_question');
        $this->addSql('ALTER TABLE variant ADD question_id INT NOT NULL');
        $this->addSql('ALTER TABLE variant ADD CONSTRAINT FK_F143BFAD1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX variant_question_idx ON variant (title, question_id)');
        $this->addSql('CREATE INDEX IDX_F143BFAD1E27F6BF ON variant (question_id)');
    }
}
