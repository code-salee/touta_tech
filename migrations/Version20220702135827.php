<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220702135827 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activite (id INT AUTO_INCREMENT NOT NULL, users_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, date DATE NOT NULL, lieu VARCHAR(255) NOT NULL, INDEX IDX_B875551567B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE admin (id INT NOT NULL, isblocked TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feedback (id INT AUTO_INCREMENT NOT NULL, users_id INT DEFAULT NULL, activites_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, INDEX IDX_D229445867B3B43D (users_id), INDEX IDX_D22944585B8C31B7 (activites_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE metier (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partenaire (id INT AUTO_INCREMENT NOT NULL, superadmin_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, INDEX IDX_32FFA373A2138BC1 (superadmin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personne (id INT AUTO_INCREMENT NOT NULL, role_id INT DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_FCEC9EFD60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE superadmin (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT NOT NULL, statut VARCHAR(255) NOT NULL, is_blocked TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B875551567B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE admin ADD CONSTRAINT FK_880E0D76BF396750 FOREIGN KEY (id) REFERENCES personne (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D229445867B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D22944585B8C31B7 FOREIGN KEY (activites_id) REFERENCES activite (id)');
        $this->addSql('ALTER TABLE partenaire ADD CONSTRAINT FK_32FFA373A2138BC1 FOREIGN KEY (superadmin_id) REFERENCES superadmin (id)');
        $this->addSql('ALTER TABLE personne ADD CONSTRAINT FK_FCEC9EFD60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE superadmin ADD CONSTRAINT FK_39D87404BF396750 FOREIGN KEY (id) REFERENCES personne (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649BF396750 FOREIGN KEY (id) REFERENCES personne (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D22944585B8C31B7');
        $this->addSql('ALTER TABLE admin DROP FOREIGN KEY FK_880E0D76BF396750');
        $this->addSql('ALTER TABLE superadmin DROP FOREIGN KEY FK_39D87404BF396750');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649BF396750');
        $this->addSql('ALTER TABLE personne DROP FOREIGN KEY FK_FCEC9EFD60322AC');
        $this->addSql('ALTER TABLE partenaire DROP FOREIGN KEY FK_32FFA373A2138BC1');
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B875551567B3B43D');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D229445867B3B43D');
        $this->addSql('DROP TABLE activite');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE feedback');
        $this->addSql('DROP TABLE metier');
        $this->addSql('DROP TABLE partenaire');
        $this->addSql('DROP TABLE personne');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE superadmin');
        $this->addSql('DROP TABLE user');
    }
}
