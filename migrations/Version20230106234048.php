<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230106234048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE category_webpage (id UUID NOT NULL, category_id INT NOT NULL, webpage_id BIGINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX ix__category_webpage_id ON category_webpage (webpage_id)');
        $this->addSql('CREATE UNIQUE INDEX ix_uq__category_webpage ON category_webpage (category_id, webpage_id)');
        $this->addSql('COMMENT ON COLUMN category_webpage.id IS \'(DC2Type:ulid)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE category_webpage');
    }
}
