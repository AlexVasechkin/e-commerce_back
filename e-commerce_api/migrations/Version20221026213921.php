<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221026213921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE product_property_value (id UUID NOT NULL, product_id BIGINT NOT NULL, property_id INT NOT NULL, string_value VARCHAR(255) DEFAULT NULL, int_value INT DEFAULT NULL, float_value DOUBLE PRECISION DEFAULT NULL, datetime_value TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX ix_uq_property_value ON product_property_value (product_id, property_id)');
        $this->addSql('CREATE INDEX ix_property ON product_property_value (property_id)');
        $this->addSql('COMMENT ON COLUMN product_property_value.id IS \'(DC2Type:ulid)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE product_property_value');
    }
}
