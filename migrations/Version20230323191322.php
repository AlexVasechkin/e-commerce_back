<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230323191322 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE product_group_category_item (id UUID NOT NULL, product_group_id INT NOT NULL, product_category_id INT NOT NULL, sort_order SMALLINT DEFAULT 500 NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX ix_prdct_grp_ctgry__category_id ON product_group_category_item (product_category_id)');
        $this->addSql('CREATE UNIQUE INDEX ix_uq_prdct_grp_ctgry ON product_group_category_item (product_group_id, product_category_id)');
        $this->addSql('COMMENT ON COLUMN product_group_category_item.id IS \'(DC2Type:ulid)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE product_group_category_item');
    }
}
