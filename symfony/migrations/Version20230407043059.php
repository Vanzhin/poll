<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230407043059 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE variant DROP INDEX variant_question_idx, ADD UNIQUE variant_question_idx (title(255), question_id) USING BTREE');
        $this->addSql('ALTER TABLE variant CHANGE title title LONGTEXT NOT NULL, CHANGE weight weight SMALLINT DEFAULT 100 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE variant CHANGE title title VARCHAR(700) NOT NULL, CHANGE weight weight SMALLINT DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE variant DROP INDEX variant_question_idx');
        $this->addSql('CREATE UNIQUE INDEX variant_question_idx ON variant (title, question_id)');

    }
}
