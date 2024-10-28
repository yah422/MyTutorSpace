<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241028151001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lecon_niveau DROP FOREIGN KEY FK_24E55585B3E9C81');
        $this->addSql('ALTER TABLE lecon_niveau DROP FOREIGN KEY FK_24E55585EC1308A5');
        $this->addSql('DROP TABLE lecon_niveau');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lecon_niveau (lecon_id INT NOT NULL, niveau_id INT NOT NULL, INDEX IDX_24E55585B3E9C81 (niveau_id), INDEX IDX_24E55585EC1308A5 (lecon_id), PRIMARY KEY(lecon_id, niveau_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE lecon_niveau ADD CONSTRAINT FK_24E55585B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lecon_niveau ADD CONSTRAINT FK_24E55585EC1308A5 FOREIGN KEY (lecon_id) REFERENCES lecon (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
