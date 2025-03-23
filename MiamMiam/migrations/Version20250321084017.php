<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250321084017 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_magasin (article_id INT NOT NULL, magasin_id INT NOT NULL, INDEX IDX_B97D1B907294869C (article_id), INDEX IDX_B97D1B9020096AE3 (magasin_id), PRIMARY KEY(article_id, magasin_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE liste (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, periode VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE liste_article (id INT AUTO_INCREMENT NOT NULL, article_id INT NOT NULL, liste_id INT NOT NULL, quantite INT NOT NULL, date_ajout DATETIME NOT NULL, INDEX IDX_B30096377294869C (article_id), INDEX IDX_B3009637E85441D8 (liste_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE magasin (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_article (type_id INT NOT NULL, article_id INT NOT NULL, INDEX IDX_2A1B6193C54C8C93 (type_id), INDEX IDX_2A1B61937294869C (article_id), PRIMARY KEY(type_id, article_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, pseudo VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, is_admin TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_liste (user_id INT NOT NULL, liste_id INT NOT NULL, INDEX IDX_1E30D1ACA76ED395 (user_id), INDEX IDX_1E30D1ACE85441D8 (liste_id), PRIMARY KEY(user_id, liste_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_magasin ADD CONSTRAINT FK_B97D1B907294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_magasin ADD CONSTRAINT FK_B97D1B9020096AE3 FOREIGN KEY (magasin_id) REFERENCES magasin (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE liste_article ADD CONSTRAINT FK_B30096377294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE liste_article ADD CONSTRAINT FK_B3009637E85441D8 FOREIGN KEY (liste_id) REFERENCES liste (id)');
        $this->addSql('ALTER TABLE type_article ADD CONSTRAINT FK_2A1B6193C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE type_article ADD CONSTRAINT FK_2A1B61937294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_liste ADD CONSTRAINT FK_1E30D1ACA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_liste ADD CONSTRAINT FK_1E30D1ACE85441D8 FOREIGN KEY (liste_id) REFERENCES liste (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_magasin DROP FOREIGN KEY FK_B97D1B907294869C');
        $this->addSql('ALTER TABLE article_magasin DROP FOREIGN KEY FK_B97D1B9020096AE3');
        $this->addSql('ALTER TABLE liste_article DROP FOREIGN KEY FK_B30096377294869C');
        $this->addSql('ALTER TABLE liste_article DROP FOREIGN KEY FK_B3009637E85441D8');
        $this->addSql('ALTER TABLE type_article DROP FOREIGN KEY FK_2A1B6193C54C8C93');
        $this->addSql('ALTER TABLE type_article DROP FOREIGN KEY FK_2A1B61937294869C');
        $this->addSql('ALTER TABLE user_liste DROP FOREIGN KEY FK_1E30D1ACA76ED395');
        $this->addSql('ALTER TABLE user_liste DROP FOREIGN KEY FK_1E30D1ACE85441D8');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE article_magasin');
        $this->addSql('DROP TABLE liste');
        $this->addSql('DROP TABLE liste_article');
        $this->addSql('DROP TABLE magasin');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE type_article');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_liste');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
