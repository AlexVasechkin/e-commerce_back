<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230312154629 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE product_group ADD is_to_homepage BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE product_group ADD homepage_sort INT DEFAULT 500 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE product_group DROP is_to_homepage');
        $this->addSql('ALTER TABLE product_group DROP homepage_sort');
    }
}
