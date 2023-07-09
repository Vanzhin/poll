<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230709073227 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'role ROLE_SUPER_ADMIN created';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("UPDATE user SET roles = REPLACE(roles,'ROLE_ADMIN','ROLE_SUPER_ADMIN')");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("UPDATE user SET roles = REPLACE(roles,'ROLE_SUPER_ADMIN','ROLE_ADMIN')");
    }
}
