<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190903140158 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE task ADD owner_id INT NOT NULL, ADD priority_id INT NOT NULL, ADD periodicity_id INT NOT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB257E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25497B19F9 FOREIGN KEY (priority_id) REFERENCES priority (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB2533E79D0D FOREIGN KEY (periodicity_id) REFERENCES periodicity (id)');
        $this->addSql('CREATE INDEX IDX_527EDB257E3C61F9 ON task (owner_id)');
        $this->addSql('CREATE INDEX IDX_527EDB25497B19F9 ON task (priority_id)');
        $this->addSql('CREATE INDEX IDX_527EDB2533E79D0D ON task (periodicity_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB257E3C61F9');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25497B19F9');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB2533E79D0D');
        $this->addSql('DROP INDEX IDX_527EDB257E3C61F9 ON task');
        $this->addSql('DROP INDEX IDX_527EDB25497B19F9 ON task');
        $this->addSql('DROP INDEX IDX_527EDB2533E79D0D ON task');
        $this->addSql('ALTER TABLE task DROP owner_id, DROP priority_id, DROP periodicity_id');
    }
}
