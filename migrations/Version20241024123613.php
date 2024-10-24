<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241024123613 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ressource_lien (ressource_id INT NOT NULL, lien_id INT NOT NULL, INDEX IDX_558DB43CFC6CD52A (ressource_id), INDEX IDX_558DB43CEDAAC352 (lien_id), PRIMARY KEY(ressource_id, lien_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ressource_lien ADD CONSTRAINT FK_558DB43CFC6CD52A FOREIGN KEY (ressource_id) REFERENCES ressource (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ressource_lien ADD CONSTRAINT FK_558DB43CEDAAC352 FOREIGN KEY (lien_id) REFERENCES lien (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ressource_lien DROP FOREIGN KEY FK_558DB43CFC6CD52A');
        $this->addSql('ALTER TABLE ressource_lien DROP FOREIGN KEY FK_558DB43CEDAAC352');
        $this->addSql('DROP TABLE ressource_lien');
    }
}
