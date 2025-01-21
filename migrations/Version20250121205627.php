<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250121205627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tutor_availability ADD start_time DATETIME NOT NULL, ADD end_time DATETIME NOT NULL, ADD is_booked TINYINT(1) NOT NULL, DROP start, DROP end');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tutor_availability ADD start VARCHAR(255) NOT NULL, ADD end VARCHAR(255) NOT NULL, DROP start_time, DROP end_time, DROP is_booked');
    }
}
