<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221127165734 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE webpage_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE webpage (id BIGINT NOT NULL, parent_id BIGINT DEFAULT NULL, product_id BIGINT DEFAULT NULL, name VARCHAR(128) NOT NULL, pagetitle VARCHAR(200) DEFAULT NULL, headline VARCHAR(255) DEFAULT NULL, description TEXT DEFAULT NULL, content TEXT DEFAULT NULL, alias VARCHAR(2000) NOT NULL, is_active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX ix_uq_webpage_name ON webpage (name)');
        $this->addSql('CREATE UNIQUE INDEX ix_uq_webpage_alias ON webpage (alias, product_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE webpage_id_seq CASCADE');
        $this->addSql('DROP TABLE webpage');
    }
}
