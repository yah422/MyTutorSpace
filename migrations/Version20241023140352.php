<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241023140352 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE niveau_lecon DROP FOREIGN KEY FK_7181D648B3E9C81');
        $this->addSql('ALTER TABLE niveau_lecon DROP FOREIGN KEY FK_7181D648EC1308A5');
        $this->addSql('ALTER TABLE lecon_niveau DROP FOREIGN KEY FK_24E55585B3E9C81');
        $this->addSql('ALTER TABLE lecon_niveau DROP FOREIGN KEY FK_24E55585EC1308A5');
        $this->addSql('DROP TABLE niveau_lecon');
        $this->addSql('DROP TABLE lecon_niveau');
        $this->addSql('ALTER TABLE exercice DROP FOREIGN KEY FK_E418C74D8EB23357');
        $this->addSql('ALTER TABLE exercice DROP FOREIGN KEY FK_E418C74DC54C8C93');
        $this->addSql('ALTER TABLE exercice DROP FOREIGN KEY FK_E418C74DEC1308A5');
        $this->addSql('DROP INDEX IDX_E418C74DEC1308A5 ON exercice');
        $this->addSql('DROP INDEX IDX_E418C74DC54C8C93 ON exercice');
        $this->addSql('DROP INDEX IDX_E418C74D8EB23357 ON exercice');
        $this->addSql('ALTER TABLE exercice DROP lecon_id, DROP type_id, DROP types_id');
        $this->addSql('ALTER TABLE lecon DROP FOREIGN KEY FK_94E6242E82350831');
        $this->addSql('ALTER TABLE lecon DROP FOREIGN KEY FK_94E6242EF46CD258');
        $this->addSql('DROP INDEX IDX_94E6242EF46CD258 ON lecon');
        $this->addSql('DROP INDEX IDX_94E6242E82350831 ON lecon');
        $this->addSql('ALTER TABLE lecon DROP matiere_id, DROP matieres_id');
        $this->addSql('ALTER TABLE ressource DROP FOREIGN KEY FK_939F4544192C7251');
        $this->addSql('ALTER TABLE ressource DROP FOREIGN KEY FK_939F454489D40298');
        $this->addSql('DROP INDEX IDX_939F454489D40298 ON ressource');
        $this->addSql('DROP INDEX IDX_939F4544192C7251 ON ressource');
        $this->addSql('ALTER TABLE ressource DROP exercice_id, DROP exercices_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE niveau_lecon (niveau_id INT NOT NULL, lecon_id INT NOT NULL, INDEX IDX_7181D648B3E9C81 (niveau_id), INDEX IDX_7181D648EC1308A5 (lecon_id), PRIMARY KEY(niveau_id, lecon_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE lecon_niveau (lecon_id INT NOT NULL, niveau_id INT NOT NULL, INDEX IDX_24E55585EC1308A5 (lecon_id), INDEX IDX_24E55585B3E9C81 (niveau_id), PRIMARY KEY(lecon_id, niveau_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE niveau_lecon ADD CONSTRAINT FK_7181D648B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE niveau_lecon ADD CONSTRAINT FK_7181D648EC1308A5 FOREIGN KEY (lecon_id) REFERENCES lecon (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lecon_niveau ADD CONSTRAINT FK_24E55585B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lecon_niveau ADD CONSTRAINT FK_24E55585EC1308A5 FOREIGN KEY (lecon_id) REFERENCES lecon (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE exercice ADD lecon_id INT DEFAULT NULL, ADD type_id INT DEFAULT NULL, ADD types_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE exercice ADD CONSTRAINT FK_E418C74D8EB23357 FOREIGN KEY (types_id) REFERENCES type (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE exercice ADD CONSTRAINT FK_E418C74DC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE exercice ADD CONSTRAINT FK_E418C74DEC1308A5 FOREIGN KEY (lecon_id) REFERENCES lecon (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_E418C74DEC1308A5 ON exercice (lecon_id)');
        $this->addSql('CREATE INDEX IDX_E418C74DC54C8C93 ON exercice (type_id)');
        $this->addSql('CREATE INDEX IDX_E418C74D8EB23357 ON exercice (types_id)');
        $this->addSql('ALTER TABLE ressource ADD exercice_id INT DEFAULT NULL, ADD exercices_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT FK_939F4544192C7251 FOREIGN KEY (exercices_id) REFERENCES exercice (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT FK_939F454489D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_939F454489D40298 ON ressource (exercice_id)');
        $this->addSql('CREATE INDEX IDX_939F4544192C7251 ON ressource (exercices_id)');
        $this->addSql('ALTER TABLE lecon ADD matiere_id INT DEFAULT NULL, ADD matieres_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lecon ADD CONSTRAINT FK_94E6242E82350831 FOREIGN KEY (matieres_id) REFERENCES matiere (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE lecon ADD CONSTRAINT FK_94E6242EF46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_94E6242EF46CD258 ON lecon (matiere_id)');
        $this->addSql('CREATE INDEX IDX_94E6242E82350831 ON lecon (matieres_id)');
    }
}
