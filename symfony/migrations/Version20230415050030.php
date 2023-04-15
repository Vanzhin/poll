<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230415050030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('UPDATE section SET question_count_to_pass  = 0 WHERE question_count_to_pass IS NULL');
        $this->addSql('ALTER TABLE section CHANGE question_count_to_pass question_count_to_pass SMALLINT DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE section CHANGE question_count_to_pass question_count_to_pass SMALLINT DEFAULT NULL');
        $this->addSql('UPDATE section SET question_count_to_pass = NULL WHERE question_count_to_pass = 0');

    }
}
