<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241107075200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lecon_user (lecon_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_3955EC52EC1308A5 (lecon_id), INDEX IDX_3955EC52A76ED395 (user_id), PRIMARY KEY(lecon_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_niveau (user_id INT NOT NULL, niveau_id INT NOT NULL, INDEX IDX_2E8DE856A76ED395 (user_id), INDEX IDX_2E8DE856B3E9C81 (niveau_id), PRIMARY KEY(user_id, niveau_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lecon_user ADD CONSTRAINT FK_3955EC52EC1308A5 FOREIGN KEY (lecon_id) REFERENCES lecon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lecon_user ADD CONSTRAINT FK_3955EC52A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_niveau ADD CONSTRAINT FK_2E8DE856A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_niveau ADD CONSTRAINT FK_2E8DE856B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lecon ADD pdf_path VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD niveau_id INT DEFAULT NULL, ADD plain_password VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649B3E9C81 ON user (niveau_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lecon_user DROP FOREIGN KEY FK_3955EC52EC1308A5');
        $this->addSql('ALTER TABLE lecon_user DROP FOREIGN KEY FK_3955EC52A76ED395');
        $this->addSql('ALTER TABLE user_niveau DROP FOREIGN KEY FK_2E8DE856A76ED395');
        $this->addSql('ALTER TABLE user_niveau DROP FOREIGN KEY FK_2E8DE856B3E9C81');
        $this->addSql('DROP TABLE lecon_user');
        $this->addSql('DROP TABLE user_niveau');
        $this->addSql('ALTER TABLE lecon DROP pdf_path');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649B3E9C81');
        $this->addSql('DROP INDEX IDX_8D93D649B3E9C81 ON user');
        $this->addSql('ALTER TABLE user DROP niveau_id, DROP plain_password');
    }
}
