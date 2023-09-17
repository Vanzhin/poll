<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230822174342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE protocol ADD test_id INT NOT NULL');
        $this->addSql('ALTER TABLE protocol ADD CONSTRAINT FK_C8C0BC4C1E5D0459 FOREIGN KEY (test_id) REFERENCES test (id)');
        $this->addSql('CREATE INDEX IDX_C8C0BC4C1E5D0459 ON protocol (test_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE protocol DROP FOREIGN KEY FK_C8C0BC4C1E5D0459');
        $this->addSql('DROP INDEX IDX_C8C0BC4C1E5D0459 ON protocol');
        $this->addSql('ALTER TABLE protocol DROP test_id');
    }
}
