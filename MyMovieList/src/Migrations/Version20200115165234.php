<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200115165234 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE movie ADD movie_list_id INT DEFAULT NULL, ADD duration INT NOT NULL, ADD movie_id INT NOT NULL');
        $this->addSql('ALTER TABLE movie ADD CONSTRAINT FK_1D5EF26F1D3854A5 FOREIGN KEY (movie_list_id) REFERENCES movielist (id)');
        $this->addSql('CREATE INDEX IDX_1D5EF26F1D3854A5 ON movie (movie_list_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE movie DROP FOREIGN KEY FK_1D5EF26F1D3854A5');
        $this->addSql('DROP INDEX IDX_1D5EF26F1D3854A5 ON movie');
        $this->addSql('ALTER TABLE movie DROP movie_list_id, DROP duration, DROP movie_id');
    }
}
