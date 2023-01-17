<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230117074311 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE division (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, title LONGTEXT NOT NULL, variant JSON NOT NULL, answer JSON NOT NULL, INDEX IDX_B6F7494EC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE section (id INT AUTO_INCREMENT NOT NULL, division_id INT NOT NULL, title LONGTEXT NOT NULL, INDEX IDX_2D737AEF41859289 (division_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test_ticket (test_id INT NOT NULL, ticket_id INT NOT NULL, INDEX IDX_79AA8D1E5D0459 (test_id), INDEX IDX_79AA8D700047D2 (ticket_id), PRIMARY KEY(test_id, ticket_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket_question (ticket_id INT NOT NULL, question_id INT NOT NULL, INDEX IDX_339EB5CF700047D2 (ticket_id), INDEX IDX_339EB5CF1E27F6BF (question_id), PRIMARY KEY(ticket_id, question_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE section ADD CONSTRAINT FK_2D737AEF41859289 FOREIGN KEY (division_id) REFERENCES division (id)');
        $this->addSql('ALTER TABLE test_ticket ADD CONSTRAINT FK_79AA8D1E5D0459 FOREIGN KEY (test_id) REFERENCES test (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE test_ticket ADD CONSTRAINT FK_79AA8D700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ticket_question ADD CONSTRAINT FK_339EB5CF700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ticket_question ADD CONSTRAINT FK_339EB5CF1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EC54C8C93');
        $this->addSql('ALTER TABLE section DROP FOREIGN KEY FK_2D737AEF41859289');
        $this->addSql('ALTER TABLE test_ticket DROP FOREIGN KEY FK_79AA8D1E5D0459');
        $this->addSql('ALTER TABLE test_ticket DROP FOREIGN KEY FK_79AA8D700047D2');
        $this->addSql('ALTER TABLE ticket_question DROP FOREIGN KEY FK_339EB5CF700047D2');
        $this->addSql('ALTER TABLE ticket_question DROP FOREIGN KEY FK_339EB5CF1E27F6BF');
        $this->addSql('DROP TABLE division');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE section');
        $this->addSql('DROP TABLE test');
        $this->addSql('DROP TABLE test_ticket');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE ticket_question');
        $this->addSql('DROP TABLE type');
    }
}
