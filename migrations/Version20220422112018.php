<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Create logs table
 */
final class Version20220422112018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create logs table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE logs(
                log_id SERIAL PRIMARY KEY,
                service_name VARCHAR(50) NOT NULL,
                recorded_at TIMESTAMP NOT NULL,
                method VARCHAR(10) NOT NULL,
                path VARCHAR(100) NOT NULL,
                protocol VARCHAR(15) NOT NULL,
                status_code INT NOT NULL,
                created_at TIMESTAMP NOT NULL
             );'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE logs');
    }
}
