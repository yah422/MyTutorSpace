<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241023181741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_lecon (user_id INT NOT NULL, lecon_id INT NOT NULL, INDEX IDX_7624DF76A76ED395 (user_id), INDEX IDX_7624DF76EC1308A5 (lecon_id), PRIMARY KEY(user_id, lecon_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_lecon ADD CONSTRAINT FK_7624DF76A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_lecon ADD CONSTRAINT FK_7624DF76EC1308A5 FOREIGN KEY (lecon_id) REFERENCES lecon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lecon ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lecon ADD CONSTRAINT FK_94E6242EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_94E6242EA76ED395 ON lecon (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_lecon DROP FOREIGN KEY FK_7624DF76A76ED395');
        $this->addSql('ALTER TABLE user_lecon DROP FOREIGN KEY FK_7624DF76EC1308A5');
        $this->addSql('DROP TABLE user_lecon');
        $this->addSql('ALTER TABLE lecon DROP FOREIGN KEY FK_94E6242EA76ED395');
        $this->addSql('DROP INDEX IDX_94E6242EA76ED395 ON lecon');
        $this->addSql('ALTER TABLE lecon DROP user_id');
    }
}
