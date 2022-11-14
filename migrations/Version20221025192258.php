<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221025192258 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_category_item (id UUID NOT NULL, product_id BIGINT NOT NULL, category_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX ix_category_id ON product_category_item (category_id)');
        $this->addSql('CREATE UNIQUE INDEX ix_uq_product_category_item ON product_category_item (product_id, category_id)');
        $this->addSql('COMMENT ON COLUMN product_category_item.id IS \'(DC2Type:ulid)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE product_category_item');
    }
}
