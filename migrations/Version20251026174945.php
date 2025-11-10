<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251026174945 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add slug to program, backfill, then set NOT NULL + UNIQUE';
    }

    public function up(Schema $schema): void
    {
        // $this->addSql('ALTER TABLE program ADD slug VARCHAR(255) DEFAULT NULL');
        $this->addSql("UPDATE program SET slug = CONCAT (LOWER(REPLACE(title, ' ', '-')), '-', id) WHERE slug IS NULL OR slug = ''");
        $this->addSql('ALTER TABLE program MODIFY slug VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_92ED7784989D9B62 ON program (slug)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_92ED7784989D9B62 ON program');
        $this->addSql('ALTER TABLE program DROP slug');
    }
}
