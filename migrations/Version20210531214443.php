<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210531214443 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task ADD usertd_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25C090F1B6 FOREIGN KEY (usertd_id) REFERENCES usertd (id)');
        $this->addSql('CREATE INDEX IDX_527EDB25C090F1B6 ON task (usertd_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25C090F1B6');
        $this->addSql('DROP INDEX IDX_527EDB25C090F1B6 ON task');
        $this->addSql('ALTER TABLE task DROP usertd_id');
    }
}
