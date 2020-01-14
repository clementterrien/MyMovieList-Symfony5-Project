<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200114210119 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE list_item DROP FOREIGN KEY FK_5AD5FAF7A6D70A54');
        $this->addSql('CREATE TABLE listitem (id INT AUTO_INCREMENT NOT NULL, movielist_id INT NOT NULL, movie_id INT NOT NULL, INDEX IDX_43F3A0C93D5A6C7D (movielist_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movielist (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_D6F3A4F0A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE listitem ADD CONSTRAINT FK_43F3A0C93D5A6C7D FOREIGN KEY (movielist_id) REFERENCES movielist (id)');
        $this->addSql('ALTER TABLE movielist ADD CONSTRAINT FK_D6F3A4F0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE list_item');
        $this->addSql('DROP TABLE movie_list');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE listitem DROP FOREIGN KEY FK_43F3A0C93D5A6C7D');
        $this->addSql('CREATE TABLE list_item (id INT AUTO_INCREMENT NOT NULL, list_id INT NOT NULL, movie_id INT NOT NULL, UNIQUE INDEX UNIQ_5AD5FAF7A6D70A54 (list_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE movie_list (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, user_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE list_item ADD CONSTRAINT FK_5AD5FAF7A6D70A54 FOREIGN KEY (list_id) REFERENCES movie_list (id)');
        $this->addSql('DROP TABLE listitem');
        $this->addSql('DROP TABLE movielist');
    }
}
