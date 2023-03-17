<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230317092929 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE result ADD test_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC1131E5D0459 FOREIGN KEY (test_id) REFERENCES test (id)');
        $this->addSql('CREATE INDEX IDX_136AC1131E5D0459 ON result (test_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC1131E5D0459');
        $this->addSql('DROP INDEX IDX_136AC1131E5D0459 ON result');
        $this->addSql('ALTER TABLE result DROP test_id');
    }
}
