<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230227142052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_question DROP FOREIGN KEY FK_18DCD3512469DE2');
        $this->addSql('ALTER TABLE category_question DROP FOREIGN KEY FK_18DCD351E27F6BF');
        $this->addSql('DROP TABLE category_question');
        $this->addSql('ALTER TABLE section DROP FOREIGN KEY FK_2D737AEF12469DE2');
        $this->addSql('DROP INDEX IDX_2D737AEF12469DE2 ON section');
        $this->addSql('ALTER TABLE section DROP category_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_question (category_id INT NOT NULL, question_id INT NOT NULL, INDEX IDX_18DCD351E27F6BF (question_id), INDEX IDX_18DCD3512469DE2 (category_id), PRIMARY KEY(category_id, question_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE category_question ADD CONSTRAINT FK_18DCD3512469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_question ADD CONSTRAINT FK_18DCD351E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE section ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE section ADD CONSTRAINT FK_2D737AEF12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_2D737AEF12469DE2 ON section (category_id)');
    }
}
