<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190519164620 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE project__projects (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, organization_id INT DEFAULT NULL, name VARCHAR(150) NOT NULL, slug VARCHAR(150) NOT NULL, short_description VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, website_url VARCHAR(255) DEFAULT NULL, is_published TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_636FB3135E237E06 (name), UNIQUE INDEX UNIQ_636FB313989D9B62 (slug), INDEX IDX_636FB313A76ED395 (user_id), INDEX IDX_636FB31332C8A3DE (organization_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project__social_networks (url VARCHAR(255) NOT NULL, project_id INT DEFAULT NULL, network VARCHAR(15) NOT NULL, INDEX IDX_97D60482166D1F9C (project_id), PRIMARY KEY(url)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user__users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(60) NOT NULL, email VARCHAR(80) NOT NULL, is_active TINYINT(1) NOT NULL, password VARCHAR(150) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, last_connected_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organization__organizations (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(150) NOT NULL, slug VARCHAR(150) NOT NULL, short_description VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, website_url VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_1D3EBFE25E237E06 (name), UNIQUE INDEX UNIQ_1D3EBFE2989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organization__members (organization_id INT NOT NULL, user_id INT NOT NULL, joined_at DATETIME NOT NULL, INDEX IDX_DEF0F12032C8A3DE (organization_id), INDEX IDX_DEF0F120A76ED395 (user_id), PRIMARY KEY(organization_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organization__social_networks (url VARCHAR(255) NOT NULL, organization_id INT DEFAULT NULL, network VARCHAR(15) NOT NULL, INDEX IDX_DCB8794032C8A3DE (organization_id), PRIMARY KEY(url)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project__projects ADD CONSTRAINT FK_636FB313A76ED395 FOREIGN KEY (user_id) REFERENCES user__users (id)');
        $this->addSql('ALTER TABLE project__projects ADD CONSTRAINT FK_636FB31332C8A3DE FOREIGN KEY (organization_id) REFERENCES organization__organizations (id)');
        $this->addSql('ALTER TABLE project__social_networks ADD CONSTRAINT FK_97D60482166D1F9C FOREIGN KEY (project_id) REFERENCES project__projects (id)');
        $this->addSql('ALTER TABLE organization__members ADD CONSTRAINT FK_DEF0F12032C8A3DE FOREIGN KEY (organization_id) REFERENCES organization__organizations (id)');
        $this->addSql('ALTER TABLE organization__members ADD CONSTRAINT FK_DEF0F120A76ED395 FOREIGN KEY (user_id) REFERENCES user__users (id)');
        $this->addSql('ALTER TABLE organization__social_networks ADD CONSTRAINT FK_DCB8794032C8A3DE FOREIGN KEY (organization_id) REFERENCES organization__organizations (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project__social_networks DROP FOREIGN KEY FK_97D60482166D1F9C');
        $this->addSql('ALTER TABLE project__projects DROP FOREIGN KEY FK_636FB313A76ED395');
        $this->addSql('ALTER TABLE organization__members DROP FOREIGN KEY FK_DEF0F120A76ED395');
        $this->addSql('ALTER TABLE project__projects DROP FOREIGN KEY FK_636FB31332C8A3DE');
        $this->addSql('ALTER TABLE organization__members DROP FOREIGN KEY FK_DEF0F12032C8A3DE');
        $this->addSql('ALTER TABLE organization__social_networks DROP FOREIGN KEY FK_DCB8794032C8A3DE');
        $this->addSql('DROP TABLE project__projects');
        $this->addSql('DROP TABLE project__social_networks');
        $this->addSql('DROP TABLE user__users');
        $this->addSql('DROP TABLE organization__organizations');
        $this->addSql('DROP TABLE organization__members');
        $this->addSql('DROP TABLE organization__social_networks');
    }
}
