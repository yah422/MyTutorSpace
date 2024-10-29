<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241029074314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE exercice (id INT AUTO_INCREMENT NOT NULL, lecon_id INT DEFAULT NULL, type_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(500) NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_E418C74DEC1308A5 (lecon_id), INDEX IDX_E418C74DC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lecon (id INT AUTO_INCREMENT NOT NULL, matiere_id INT NOT NULL, user_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_94E6242EF46CD258 (matiere_id), INDEX IDX_94E6242EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lien (id INT AUTO_INCREMENT NOT NULL, valeur VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matiere (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, description VARCHAR(500) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE niveau (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE niveau_lecon (niveau_id INT NOT NULL, lecon_id INT NOT NULL, INDEX IDX_7181D648B3E9C81 (niveau_id), INDEX IDX_7181D648EC1308A5 (lecon_id), PRIMARY KEY(niveau_id, lecon_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ressource (id INT AUTO_INCREMENT NOT NULL, exercice_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, contenu VARCHAR(255) NOT NULL, INDEX IDX_939F454489D40298 (exercice_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ressource_lien (ressource_id INT NOT NULL, lien_id INT NOT NULL, INDEX IDX_558DB43CFC6CD52A (ressource_id), INDEX IDX_558DB43CEDAAC352 (lien_id), PRIMARY KEY(ressource_id, lien_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_matiere (user_id INT NOT NULL, matiere_id INT NOT NULL, INDEX IDX_C8194940A76ED395 (user_id), INDEX IDX_C8194940F46CD258 (matiere_id), PRIMARY KEY(user_id, matiere_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_lecon (user_id INT NOT NULL, lecon_id INT NOT NULL, INDEX IDX_7624DF76A76ED395 (user_id), INDEX IDX_7624DF76EC1308A5 (lecon_id), PRIMARY KEY(user_id, lecon_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE exercice ADD CONSTRAINT FK_E418C74DEC1308A5 FOREIGN KEY (lecon_id) REFERENCES lecon (id)');
        $this->addSql('ALTER TABLE exercice ADD CONSTRAINT FK_E418C74DC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE lecon ADD CONSTRAINT FK_94E6242EF46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id)');
        $this->addSql('ALTER TABLE lecon ADD CONSTRAINT FK_94E6242EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE niveau_lecon ADD CONSTRAINT FK_7181D648B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE niveau_lecon ADD CONSTRAINT FK_7181D648EC1308A5 FOREIGN KEY (lecon_id) REFERENCES lecon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT FK_939F454489D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id)');
        $this->addSql('ALTER TABLE ressource_lien ADD CONSTRAINT FK_558DB43CFC6CD52A FOREIGN KEY (ressource_id) REFERENCES ressource (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ressource_lien ADD CONSTRAINT FK_558DB43CEDAAC352 FOREIGN KEY (lien_id) REFERENCES lien (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_matiere ADD CONSTRAINT FK_C8194940A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_matiere ADD CONSTRAINT FK_C8194940F46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_lecon ADD CONSTRAINT FK_7624DF76A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_lecon ADD CONSTRAINT FK_7624DF76EC1308A5 FOREIGN KEY (lecon_id) REFERENCES lecon (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exercice DROP FOREIGN KEY FK_E418C74DEC1308A5');
        $this->addSql('ALTER TABLE exercice DROP FOREIGN KEY FK_E418C74DC54C8C93');
        $this->addSql('ALTER TABLE lecon DROP FOREIGN KEY FK_94E6242EF46CD258');
        $this->addSql('ALTER TABLE lecon DROP FOREIGN KEY FK_94E6242EA76ED395');
        $this->addSql('ALTER TABLE niveau_lecon DROP FOREIGN KEY FK_7181D648B3E9C81');
        $this->addSql('ALTER TABLE niveau_lecon DROP FOREIGN KEY FK_7181D648EC1308A5');
        $this->addSql('ALTER TABLE ressource DROP FOREIGN KEY FK_939F454489D40298');
        $this->addSql('ALTER TABLE ressource_lien DROP FOREIGN KEY FK_558DB43CFC6CD52A');
        $this->addSql('ALTER TABLE ressource_lien DROP FOREIGN KEY FK_558DB43CEDAAC352');
        $this->addSql('ALTER TABLE user_matiere DROP FOREIGN KEY FK_C8194940A76ED395');
        $this->addSql('ALTER TABLE user_matiere DROP FOREIGN KEY FK_C8194940F46CD258');
        $this->addSql('ALTER TABLE user_lecon DROP FOREIGN KEY FK_7624DF76A76ED395');
        $this->addSql('ALTER TABLE user_lecon DROP FOREIGN KEY FK_7624DF76EC1308A5');
        $this->addSql('DROP TABLE exercice');
        $this->addSql('DROP TABLE lecon');
        $this->addSql('DROP TABLE lien');
        $this->addSql('DROP TABLE matiere');
        $this->addSql('DROP TABLE niveau');
        $this->addSql('DROP TABLE niveau_lecon');
        $this->addSql('DROP TABLE ressource');
        $this->addSql('DROP TABLE ressource_lien');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_matiere');
        $this->addSql('DROP TABLE user_lecon');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
