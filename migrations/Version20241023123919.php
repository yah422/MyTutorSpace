<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241023123919 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ressource ADD exercices_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT FK_939F4544192C7251 FOREIGN KEY (exercices_id) REFERENCES exercice (id)');
        $this->addSql('CREATE INDEX IDX_939F4544192C7251 ON ressource (exercices_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ressource DROP FOREIGN KEY FK_939F4544192C7251');
        $this->addSql('DROP INDEX IDX_939F4544192C7251 ON ressource');
        $this->addSql('ALTER TABLE ressource DROP exercices_id');
    }
}
