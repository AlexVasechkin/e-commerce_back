<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221104145541 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE model_item (id UUID NOT NULL, product_id BIGINT NOT NULL, product_category_id INT NOT NULL, name VARCHAR(128) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX ix_model_item_name_and_category ON model_item (name, product_category_id)');
        $this->addSql('CREATE INDEX ix_model_item_category ON model_item (product_category_id)');
        $this->addSql('CREATE UNIQUE INDEX ix_uq_model_item_product ON model_item (product_id)');
        $this->addSql('COMMENT ON COLUMN model_item.id IS \'(DC2Type:ulid)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE model_item');
    }
}
