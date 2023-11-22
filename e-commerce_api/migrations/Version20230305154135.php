<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230305154135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE product_group_item (id UUID NOT NULL, product_id BIGINT NOT NULL, product_group_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX ix_product_group_item__group_id ON product_group_item (product_group_id)');
        $this->addSql('CREATE UNIQUE INDEX ix_uq_product_group_item ON product_group_item (product_id, product_group_id)');
        $this->addSql('COMMENT ON COLUMN product_group_item.id IS \'(DC2Type:ulid)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE product_group_item');
    }
}
