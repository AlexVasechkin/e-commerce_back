<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230106224658 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP INDEX ix_uq_webpage_alias');
        $this->addSql('ALTER TABLE webpage DROP product_id');
        $this->addSql('CREATE UNIQUE INDEX ix_uq_webpage_alias ON webpage (alias)');
    }

    public function down(Schema $schema): void
    {}
}
