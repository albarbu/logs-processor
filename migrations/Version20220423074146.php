<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Create logs_processing table
 */
final class Version20220423074146 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create logs_processing table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE logs_processing(
                log_processing_id SERIAL PRIMARY KEY,
                file_path VARCHAR(255) NOT NULL,
                started_at TIMESTAMP NOT NULL,
                finished_at TIMESTAMP DEFAULT NULL,
                last_processed_line INT NOT NULL DEFAULT 0,
                updated_at TIMESTAMP NOT NULL
            )
        ');

        $this->addSql('ALTER TABLE logs_processing ALTER COLUMN started_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE logs_processing ALTER COLUMN updated_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('CREATE UNIQUE INDEX logs_processing_file_path_uq ON logs_processing(file_path)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX logs_processing_file_path_uq');
        $this->addSql('DROP TABLE logs_processing');
    }
}
