<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221118234810 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP INDEX ix_uq_vendor');
        $this->addSql('ALTER TABLE vendor DROP code');
        $this->addSql('CREATE UNIQUE INDEX ix_uq_vendor ON vendor (name)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX ix_uq_vendor');
        $this->addSql('ALTER TABLE vendor ADD code VARCHAR(128) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX ix_uq_vendor ON vendor (code)');
    }
}
