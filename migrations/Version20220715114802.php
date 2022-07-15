<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220715114802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activite ADD admin_id INT DEFAULT NULL, ADD etat INT NOT NULL');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B8755515642B8210 FOREIGN KEY (admin_id) REFERENCES admin (id)');
        $this->addSql('CREATE INDEX IDX_B8755515642B8210 ON activite (admin_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B8755515642B8210');
        $this->addSql('DROP INDEX IDX_B8755515642B8210 ON activite');
        $this->addSql('ALTER TABLE activite DROP admin_id, DROP etat');
    }
}
