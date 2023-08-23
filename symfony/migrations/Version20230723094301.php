<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230723094301 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profile ADD email VARCHAR(100) NOT NULL, ADD phone VARCHAR(11) DEFAULT NULL');
        $this->addSql('UPDATE profile INNER JOIN user ON profile.id = user.profile_id SET profile.email = user.email');
        $this->addSql('ALTER TABLE user CHANGE is_active is_active TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profile DROP email, DROP phone');
        $this->addSql('ALTER TABLE `user` CHANGE is_active is_active TINYINT(1) DEFAULT 1 NOT NULL');
    }
}
