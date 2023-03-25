<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230322063151 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE subtitle (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, correct_id INT NOT NULL, title VARCHAR(500) NOT NULL, INDEX IDX_518597B11E27F6BF (question_id), INDEX IDX_518597B134F47602 (correct_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE subtitle ADD CONSTRAINT FK_518597B11E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE subtitle ADD CONSTRAINT FK_518597B134F47602 FOREIGN KEY (correct_id) REFERENCES variant (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subtitle DROP FOREIGN KEY FK_518597B11E27F6BF');
        $this->addSql('ALTER TABLE subtitle DROP FOREIGN KEY FK_518597B134F47602');
        $this->addSql('DROP TABLE subtitle');
    }
}
