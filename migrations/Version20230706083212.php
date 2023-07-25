<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230706083212 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE filter_state (id UUID NOT NULL, name VARCHAR(150) NOT NULL, state JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX ix_filter_state__name ON filter_state (name)');
        $this->addSql('COMMENT ON COLUMN filter_state.id IS \'(DC2Type:ulid)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE filter_state');
    }
}
