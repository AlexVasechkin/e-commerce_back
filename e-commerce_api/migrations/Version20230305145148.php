<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230305145148 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE product_group_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE product_group (id INT NOT NULL, name VARCHAR(200) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX ix_uq_product_group__name ON product_group (name)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE product_group_id_seq CASCADE');
        $this->addSql('DROP TABLE product_group');
    }
}
