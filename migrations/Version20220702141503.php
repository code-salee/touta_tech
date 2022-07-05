<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220702141503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activite ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B8755515A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B8755515A76ED395 ON activite (user_id)');
        $this->addSql('ALTER TABLE feedback ADD activite_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D22944589B0F88B1 FOREIGN KEY (activite_id) REFERENCES activite (id)');
        $this->addSql('CREATE INDEX IDX_D22944589B0F88B1 ON feedback (activite_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B8755515A76ED395');
        $this->addSql('DROP INDEX IDX_B8755515A76ED395 ON activite');
        $this->addSql('ALTER TABLE activite DROP user_id');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D22944589B0F88B1');
        $this->addSql('DROP INDEX IDX_D22944589B0F88B1 ON feedback');
        $this->addSql('ALTER TABLE feedback DROP activite_id');
    }
}
