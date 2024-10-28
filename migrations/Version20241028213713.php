<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241028213713 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lecon ADD CONSTRAINT FK_94E6242EF46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id)');
        $this->addSql('ALTER TABLE lecon ADD CONSTRAINT FK_94E6242EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_94E6242EF46CD258 ON lecon (matiere_id)');
        $this->addSql('CREATE INDEX IDX_94E6242EA76ED395 ON lecon (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lecon DROP FOREIGN KEY FK_94E6242EF46CD258');
        $this->addSql('ALTER TABLE lecon DROP FOREIGN KEY FK_94E6242EA76ED395');
        $this->addSql('DROP INDEX IDX_94E6242EF46CD258 ON lecon');
        $this->addSql('DROP INDEX IDX_94E6242EA76ED395 ON lecon');
    }
}
