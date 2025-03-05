<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250305164728 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contenue (id INT AUTO_INCREMENT NOT NULL, id_liste_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_59359567B88FAD4D (id_liste_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contenue_articles (contenue_id INT NOT NULL, articles_id INT NOT NULL, INDEX IDX_1FA5F13A715CA2B0 (contenue_id), INDEX IDX_1FA5F13A1EBAF6CC (articles_id), PRIMARY KEY(contenue_id, articles_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contenue ADD CONSTRAINT FK_59359567B88FAD4D FOREIGN KEY (id_liste_id) REFERENCES liste (id)');
        $this->addSql('ALTER TABLE contenue_articles ADD CONSTRAINT FK_1FA5F13A715CA2B0 FOREIGN KEY (contenue_id) REFERENCES contenue (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contenue_articles ADD CONSTRAINT FK_1FA5F13A1EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contenue DROP FOREIGN KEY FK_59359567B88FAD4D');
        $this->addSql('ALTER TABLE contenue_articles DROP FOREIGN KEY FK_1FA5F13A715CA2B0');
        $this->addSql('ALTER TABLE contenue_articles DROP FOREIGN KEY FK_1FA5F13A1EBAF6CC');
        $this->addSql('DROP TABLE contenue');
        $this->addSql('DROP TABLE contenue_articles');
    }
}
