<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230106231108 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE product_webpage (id UUID NOT NULL, product_id BIGINT NOT NULL, webpage_id BIGINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX ix__product_webpage_id ON product_webpage (webpage_id)');
        $this->addSql('CREATE UNIQUE INDEX ix_uq__product_webpage ON product_webpage (product_id, webpage_id)');
        $this->addSql('COMMENT ON COLUMN product_webpage.id IS \'(DC2Type:ulid)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE product_webpage');
    }
}
