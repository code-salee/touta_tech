<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220728022648 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activite ADD superadmin_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B8755515A2138BC1 FOREIGN KEY (superadmin_id) REFERENCES superadmin (id)');
        $this->addSql('CREATE INDEX IDX_B8755515A2138BC1 ON activite (superadmin_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B8755515A2138BC1');
        $this->addSql('DROP INDEX IDX_B8755515A2138BC1 ON activite');
        $this->addSql('ALTER TABLE activite DROP superadmin_id');
    }
}
