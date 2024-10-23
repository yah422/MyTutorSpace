<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241023180440 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ressource ADD exercice_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT FK_939F454489D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id)');
        $this->addSql('CREATE INDEX IDX_939F454489D40298 ON ressource (exercice_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ressource DROP FOREIGN KEY FK_939F454489D40298');
        $this->addSql('DROP INDEX IDX_939F454489D40298 ON ressource');
        $this->addSql('ALTER TABLE ressource DROP exercice_id');
    }
}
