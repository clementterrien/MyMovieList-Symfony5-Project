<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210308151140 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE movie DROP FOREIGN KEY FK_1D5EF26F12469DE2');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE listitem');
        $this->addSql('DROP INDEX IDX_1D5EF26F12469DE2 ON movie');
        $this->addSql('ALTER TABLE movie ADD movie_list_id INT DEFAULT NULL, ADD movie_id INT NOT NULL, CHANGE category_id duration INT NOT NULL');
        $this->addSql('ALTER TABLE movie ADD CONSTRAINT FK_1D5EF26F1D3854A5 FOREIGN KEY (movie_list_id) REFERENCES movielist (id)');
        $this->addSql('CREATE INDEX IDX_1D5EF26F1D3854A5 ON movie (movie_list_id)');
        $this->addSql('ALTER TABLE movielist ADD description LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE listitem (id INT AUTO_INCREMENT NOT NULL, movielist_id INT NOT NULL, movie_id INT NOT NULL, INDEX IDX_43F3A0C93D5A6C7D (movielist_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE listitem ADD CONSTRAINT FK_43F3A0C93D5A6C7D FOREIGN KEY (movielist_id) REFERENCES movielist (id)');
        $this->addSql('ALTER TABLE movie DROP FOREIGN KEY FK_1D5EF26F1D3854A5');
        $this->addSql('DROP INDEX IDX_1D5EF26F1D3854A5 ON movie');
        $this->addSql('ALTER TABLE movie ADD category_id INT NOT NULL, DROP movie_list_id, DROP duration, DROP movie_id');
        $this->addSql('ALTER TABLE movie ADD CONSTRAINT FK_1D5EF26F12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_1D5EF26F12469DE2 ON movie (category_id)');
        $this->addSql('ALTER TABLE movielist DROP description');
    }
}
