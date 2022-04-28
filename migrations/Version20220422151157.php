<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Create column indices on the logs table
 */
final class Version20220422151157 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create column indices on the logs table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE INDEX service_name_idx ON logs (service_name)');
        $this->addSql('CREATE INDEX status_code_idx ON logs (status_code)');
        $this->addSql('CREATE INDEX recorded_at_idx ON logs (recorded_at)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX service_name_idx');
        $this->addSql('DROP INDEX status_code_idx');
        $this->addSql('DROP INDEX recorded_at_idx');
    }
}
