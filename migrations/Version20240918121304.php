<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240918121304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE profil (id INT AUTO_INCREMENT NOT NULL, id_utilisateur INT NOT NULL, date_edition DATE NOT NULL, visible TINYINT(1) NOT NULL, code_unique VARCHAR(255) NOT NULL, numero_telephone VARCHAR(20) NOT NULL, pays VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE khdle DROP FOREIGN KEY khdle_ibfk_1');
        $this->addSql('ALTER TABLE khdle DROP FOREIGN KEY khdle_ibfk_2');
        $this->addSql('ALTER TABLE khdleEstAffilie DROP FOREIGN KEY khdleEstAffilie_ibfk_2');
        $this->addSql('ALTER TABLE khdleEstAffilie DROP FOREIGN KEY khdleEstAffilie_ibfk_1');
        $this->addSql('DROP TABLE khdle');
        $this->addSql('DROP TABLE khdleEstAffilie');
        $this->addSql('DROP TABLE khdleAffiliation');
        $this->addSql('DROP TABLE khdleOrigines');
        $this->addSql('DROP TABLE khdleJeux');
        $this->addSql('ALTER TABLE utilisateur DROP visible, DROP code_unique, DROP date_edition, CHANGE login login VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE khdle (id INT AUTO_INCREMENT NOT NULL, Nom VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Genre VARCHAR(10) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Arme VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, idOrigine INT NOT NULL, idJeu INT NOT NULL, INDEX idJeu (idJeu), INDEX idOrigine (idOrigine), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE khdleEstAffilie (idPersonnage INT NOT NULL, idAffiliation INT NOT NULL, INDEX idAffiliation (idAffiliation), INDEX IDX_B0CA9D3E81E5687 (idPersonnage), PRIMARY KEY(idPersonnage, idAffiliation)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE khdleAffiliation (id INT AUTO_INCREMENT NOT NULL, Affiliation VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE khdleOrigines (id INT AUTO_INCREMENT NOT NULL, Zone VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE khdleJeux (id INT AUTO_INCREMENT NOT NULL, Nom VARCHAR(80) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE khdle ADD CONSTRAINT khdle_ibfk_1 FOREIGN KEY (idJeu) REFERENCES khdleJeux (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE khdle ADD CONSTRAINT khdle_ibfk_2 FOREIGN KEY (idOrigine) REFERENCES khdleOrigines (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE khdleEstAffilie ADD CONSTRAINT khdleEstAffilie_ibfk_2 FOREIGN KEY (idPersonnage) REFERENCES khdle (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE khdleEstAffilie ADD CONSTRAINT khdleEstAffilie_ibfk_1 FOREIGN KEY (idAffiliation) REFERENCES khdleAffiliation (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE profil');
        $this->addSql('ALTER TABLE utilisateur ADD visible TINYINT(1) NOT NULL, ADD code_unique VARCHAR(255) NOT NULL, ADD date_edition DATE NOT NULL, CHANGE login login VARCHAR(30) NOT NULL, CHANGE email email VARCHAR(100) NOT NULL, CHANGE password password VARCHAR(50) NOT NULL');
    }
}
