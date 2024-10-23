<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241023175905 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exercice ADD lecon_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE exercice ADD CONSTRAINT FK_E418C74DEC1308A5 FOREIGN KEY (lecon_id) REFERENCES lecon (id)');
        $this->addSql('CREATE INDEX IDX_E418C74DEC1308A5 ON exercice (lecon_id)');
        $this->addSql('ALTER TABLE lecon ADD matiere_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lecon ADD CONSTRAINT FK_94E6242EF46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id)');
        $this->addSql('CREATE INDEX IDX_94E6242EF46CD258 ON lecon (matiere_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exercice DROP FOREIGN KEY FK_E418C74DEC1308A5');
        $this->addSql('DROP INDEX IDX_E418C74DEC1308A5 ON exercice');
        $this->addSql('ALTER TABLE exercice DROP lecon_id');
        $this->addSql('ALTER TABLE lecon DROP FOREIGN KEY FK_94E6242EF46CD258');
        $this->addSql('DROP INDEX IDX_94E6242EF46CD258 ON lecon');
        $this->addSql('ALTER TABLE lecon DROP matiere_id');
    }
}
