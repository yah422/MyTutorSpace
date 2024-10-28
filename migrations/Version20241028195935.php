<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241028195935 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lecon_user (lecon_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_3955EC52EC1308A5 (lecon_id), INDEX IDX_3955EC52A76ED395 (user_id), PRIMARY KEY(lecon_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lecon_user ADD CONSTRAINT FK_3955EC52EC1308A5 FOREIGN KEY (lecon_id) REFERENCES lecon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lecon_user ADD CONSTRAINT FK_3955EC52A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lecon ADD matiere_id INT NOT NULL, ADD user_id INT NOT NULL, ADD niveau_id INT NOT NULL, ADD date_creation DATETIME NOT NULL, ADD description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE lecon ADD CONSTRAINT FK_94E6242EF46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id)');
        $this->addSql('ALTER TABLE lecon ADD CONSTRAINT FK_94E6242EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE lecon ADD CONSTRAINT FK_94E6242EB3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id)');
        $this->addSql('CREATE INDEX IDX_94E6242EF46CD258 ON lecon (matiere_id)');
        $this->addSql('CREATE INDEX IDX_94E6242EA76ED395 ON lecon (user_id)');
        $this->addSql('CREATE INDEX IDX_94E6242EB3E9C81 ON lecon (niveau_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lecon_user DROP FOREIGN KEY FK_3955EC52EC1308A5');
        $this->addSql('ALTER TABLE lecon_user DROP FOREIGN KEY FK_3955EC52A76ED395');
        $this->addSql('DROP TABLE lecon_user');
        $this->addSql('ALTER TABLE lecon DROP FOREIGN KEY FK_94E6242EF46CD258');
        $this->addSql('ALTER TABLE lecon DROP FOREIGN KEY FK_94E6242EA76ED395');
        $this->addSql('ALTER TABLE lecon DROP FOREIGN KEY FK_94E6242EB3E9C81');
        $this->addSql('DROP INDEX IDX_94E6242EF46CD258 ON lecon');
        $this->addSql('DROP INDEX IDX_94E6242EA76ED395 ON lecon');
        $this->addSql('DROP INDEX IDX_94E6242EB3E9C81 ON lecon');
        $this->addSql('ALTER TABLE lecon DROP matiere_id, DROP user_id, DROP niveau_id, DROP date_creation, DROP description');
    }
}
