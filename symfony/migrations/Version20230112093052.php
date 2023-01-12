<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230112093052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, content LONGTEXT NOT NULL, variant JSON NOT NULL, answer JSON NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_B6F7494EC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_question (quiz_id INT NOT NULL, question_id INT NOT NULL, INDEX IDX_6033B00B853CD175 (quiz_id), INDEX IDX_6033B00B1E27F6BF (question_id), PRIMARY KEY(quiz_id, question_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE quiz_question ADD CONSTRAINT FK_6033B00B853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quiz_question ADD CONSTRAINT FK_6033B00B1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EC54C8C93');
        $this->addSql('ALTER TABLE quiz_question DROP FOREIGN KEY FK_6033B00B853CD175');
        $this->addSql('ALTER TABLE quiz_question DROP FOREIGN KEY FK_6033B00B1E27F6BF');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE quiz_question');
    }
}
