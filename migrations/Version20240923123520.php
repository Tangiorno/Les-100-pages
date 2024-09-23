<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240923123520 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE khdlePerso DROP FOREIGN KEY fk_perso_origine');
        $this->addSql('ALTER TABLE khdlePerso DROP FOREIGN KEY fk_perso_jeu');
        $this->addSql('ALTER TABLE khdleEstAffilie DROP FOREIGN KEY fk_estaffilie_affiliation');
        $this->addSql('ALTER TABLE khdleEstAffilie DROP FOREIGN KEY fk_estaffilie_perso');
        $this->addSql('DROP TABLE khdleJeu');
        $this->addSql('DROP TABLE test');
        $this->addSql('DROP TABLE khdlePerso');
        $this->addSql('DROP TABLE khdleEstAffilie');
        $this->addSql('DROP TABLE khdleAffiliation');
        $this->addSql('DROP TABLE khdleOrigine');
        $this->addSql('ALTER TABLE utilisateur DROP profil');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE khdleJeu (idJeu INT NOT NULL, nomJeu VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(idJeu)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE test (id INT AUTO_INCREMENT NOT NULL, a VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, b VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE khdlePerso (idPerso INT NOT NULL, nom VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, genre VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, idOrigine INT DEFAULT NULL, idJeu INT DEFAULT NULL, INDEX fk_perso_jeu (idJeu), INDEX fk_perso_origine (idOrigine), PRIMARY KEY(idPerso)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE khdleEstAffilie (idPerso INT NOT NULL, idAffiliation INT NOT NULL, INDEX fk_estaffilie_affiliation (idAffiliation), INDEX IDX_B0CA9D3AAA6B93C (idPerso), PRIMARY KEY(idPerso, idAffiliation)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE khdleAffiliation (idAffiliation INT NOT NULL, nomAffiliation VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(idAffiliation)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE khdleOrigine (idOrigine INT NOT NULL, nomOrigine VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(idOrigine)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE khdlePerso ADD CONSTRAINT fk_perso_origine FOREIGN KEY (idOrigine) REFERENCES khdleOrigine (idOrigine)');
        $this->addSql('ALTER TABLE khdlePerso ADD CONSTRAINT fk_perso_jeu FOREIGN KEY (idJeu) REFERENCES khdleJeu (idJeu)');
        $this->addSql('ALTER TABLE khdleEstAffilie ADD CONSTRAINT fk_estaffilie_affiliation FOREIGN KEY (idAffiliation) REFERENCES khdleAffiliation (idAffiliation)');
        $this->addSql('ALTER TABLE khdleEstAffilie ADD CONSTRAINT fk_estaffilie_perso FOREIGN KEY (idPerso) REFERENCES khdlePerso (idPerso)');
        $this->addSql('ALTER TABLE utilisateur ADD profil TINYINT(1) NOT NULL');
    }
}
