<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230117105909 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE division ADD slug VARCHAR(100) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_101747142B36786B ON division (title)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_10174714989D9B62 ON division (slug)');
        $this->addSql('ALTER TABLE section ADD slug VARCHAR(100) NOT NULL, CHANGE title title VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2D737AEF2B36786B ON section (title)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2D737AEF989D9B62 ON section (slug)');
        $this->addSql('ALTER TABLE test ADD slug VARCHAR(100) NOT NULL, CHANGE title title VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D87F7E0C2B36786B ON test (title)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D87F7E0C989D9B62 ON test (slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_2D737AEF2B36786B ON section');
        $this->addSql('DROP INDEX UNIQ_2D737AEF989D9B62 ON section');
        $this->addSql('ALTER TABLE section DROP slug, CHANGE title title LONGTEXT NOT NULL');
        $this->addSql('DROP INDEX UNIQ_D87F7E0C2B36786B ON test');
        $this->addSql('DROP INDEX UNIQ_D87F7E0C989D9B62 ON test');
        $this->addSql('ALTER TABLE test DROP slug, CHANGE title title LONGTEXT NOT NULL');
        $this->addSql('DROP INDEX UNIQ_101747142B36786B ON division');
        $this->addSql('DROP INDEX UNIQ_10174714989D9B62 ON division');
        $this->addSql('ALTER TABLE division DROP slug');
    }
}
