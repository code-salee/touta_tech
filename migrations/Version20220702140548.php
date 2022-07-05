<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220702140548 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B875551567B3B43D');
        $this->addSql('DROP INDEX IDX_B875551567B3B43D ON activite');
        $this->addSql('ALTER TABLE activite DROP users_id');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D22944585B8C31B7');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D229445867B3B43D');
        $this->addSql('DROP INDEX IDX_D22944585B8C31B7 ON feedback');
        $this->addSql('DROP INDEX IDX_D229445867B3B43D ON feedback');
        $this->addSql('ALTER TABLE feedback DROP users_id, DROP activites_id');
        $this->addSql('ALTER TABLE user DROP structure, CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649BF396750 FOREIGN KEY (id) REFERENCES personne (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activite ADD users_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B875551567B3B43D FOREIGN KEY (users_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_B875551567B3B43D ON activite (users_id)');
        $this->addSql('ALTER TABLE feedback ADD users_id INT DEFAULT NULL, ADD activites_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D22944585B8C31B7 FOREIGN KEY (activites_id) REFERENCES activite (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D229445867B3B43D FOREIGN KEY (users_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_D22944585B8C31B7 ON feedback (activites_id)');
        $this->addSql('CREATE INDEX IDX_D229445867B3B43D ON feedback (users_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649BF396750');
        $this->addSql('ALTER TABLE user ADD structure VARCHAR(200) DEFAULT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }
}
