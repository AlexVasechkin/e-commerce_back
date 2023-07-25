<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230423131148 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE "order" (id UUID NOT NULL, name VARCHAR(255) NOT NULL, phone VARCHAR(50) NOT NULL, email VARCHAR(100) NOT NULL, status VARCHAR(60) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "order".id IS \'(DC2Type:ulid)\'');
        $this->addSql('CREATE TABLE order_product (id UUID NOT NULL, order_id UUID NOT NULL, product_id BIGINT NOT NULL, price INT NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX ix_order_product__order ON order_product (order_id)');
        $this->addSql('CREATE INDEX ix_product_product__product ON order_product (product_id)');
        $this->addSql('COMMENT ON COLUMN order_product.id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN order_product.order_id IS \'(DC2Type:ulid)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE order_product');
    }
}
