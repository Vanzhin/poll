<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230227143142 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category ADD test_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C11E5D0459 FOREIGN KEY (test_id) REFERENCES test (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64C19C11E5D0459 ON category (test_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C11E5D0459');
        $this->addSql('DROP INDEX UNIQ_64C19C11E5D0459 ON category');
        $this->addSql('ALTER TABLE category DROP test_id');
    }
}
