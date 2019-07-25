<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190725164859 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE project__teams (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, name VARCHAR(80) NOT NULL, permissions LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', created_at DATETIME NOT NULL, INDEX IDX_B5E258E166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project__team_members (member_id INT NOT NULL, team_id INT NOT NULL, INDEX IDX_341356677597D3FE (member_id), INDEX IDX_34135667296CD8AE (team_id), PRIMARY KEY(member_id, team_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project__members DROP FOREIGN KEY FK_CA6B859166D1F9C');
        $this->addSql('ALTER TABLE project__members DROP FOREIGN KEY FK_CA6B859A76ED395');
        $this->addSql('ALTER TABLE project__members DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE project__members ADD id INT NOT NULL,  CHANGE project_id project_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project__members ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE project__members MODIFY id INT NOT NULL AUTO_INCREMENT');
        $this->addSql('ALTER TABLE project__members ADD joined_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE project__members ADD CONSTRAINT FK_CA6B859166D1F9C FOREIGN KEY (project_id) REFERENCES project__projects (id)');
        $this->addSql('ALTER TABLE project__members ADD CONSTRAINT FK_CA6B859A76ED395 FOREIGN KEY (user_id) REFERENCES user__users (id)');
        $this->addSql('ALTER TABLE project__teams ADD CONSTRAINT FK_B5E258E166D1F9C FOREIGN KEY (project_id) REFERENCES project__projects (id)');
        $this->addSql('ALTER TABLE project__team_members ADD CONSTRAINT FK_341356677597D3FE FOREIGN KEY (member_id) REFERENCES project__members (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project__team_members ADD CONSTRAINT FK_34135667296CD8AE FOREIGN KEY (team_id) REFERENCES project__teams (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project__team_members DROP FOREIGN KEY FK_34135667296CD8AE');
        $this->addSql('DROP TABLE project__teams');
        $this->addSql('DROP TABLE project__team_members');
        $this->addSql('ALTER TABLE project__members MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE project__members DROP FOREIGN KEY FK_CA6B859166D1F9C');
        $this->addSql('ALTER TABLE project__members DROP FOREIGN KEY FK_CA6B859A76ED395');
        $this->addSql('ALTER TABLE project__members DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE project__members DROP id, DROP joined_at, CHANGE project_id project_id INT NOT NULL, CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE project__members ADD CONSTRAINT FK_CA6B859166D1F9C FOREIGN KEY (project_id) REFERENCES project__projects (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project__members ADD CONSTRAINT FK_CA6B859A76ED395 FOREIGN KEY (user_id) REFERENCES user__users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project__members ADD PRIMARY KEY (project_id, user_id)');
    }
}
