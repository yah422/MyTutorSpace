<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241028124755 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE niveau_lecon (niveau_id INT NOT NULL, lecon_id INT NOT NULL, INDEX IDX_7181D648B3E9C81 (niveau_id), INDEX IDX_7181D648EC1308A5 (lecon_id), PRIMARY KEY(niveau_id, lecon_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE niveau_lecon ADD CONSTRAINT FK_7181D648B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE niveau_lecon ADD CONSTRAINT FK_7181D648EC1308A5 FOREIGN KEY (lecon_id) REFERENCES lecon (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE niveau_lecon DROP FOREIGN KEY FK_7181D648B3E9C81');
        $this->addSql('ALTER TABLE niveau_lecon DROP FOREIGN KEY FK_7181D648EC1308A5');
        $this->addSql('DROP TABLE niveau_lecon');
    }
}
