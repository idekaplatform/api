<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190728085319 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE project__candidatures (id INT AUTO_INCREMENT NOT NULL, job_offer_id INT DEFAULT NULL, user_id INT DEFAULT NULL, responder_id INT DEFAULT NULL, status VARCHAR(20) NOT NULL, message VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, responded_at DATETIME DEFAULT NULL, INDEX IDX_509D3AC93481D195 (job_offer_id), INDEX IDX_509D3AC9A76ED395 (user_id), INDEX IDX_509D3AC937395ADB (responder_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project__candidatures ADD CONSTRAINT FK_509D3AC93481D195 FOREIGN KEY (job_offer_id) REFERENCES project__job_offers (id)');
        $this->addSql('ALTER TABLE project__candidatures ADD CONSTRAINT FK_509D3AC9A76ED395 FOREIGN KEY (user_id) REFERENCES user__users (id)');
        $this->addSql('ALTER TABLE project__candidatures ADD CONSTRAINT FK_509D3AC937395ADB FOREIGN KEY (responder_id) REFERENCES user__users (id)');
        $this->addSql('DROP TABLE project__candidates');
        $this->addSql('ALTER TABLE project__job_offers ADD is_active TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE project__candidates (id INT AUTO_INCREMENT NOT NULL, job_offer_id INT DEFAULT NULL, user_id INT DEFAULT NULL, message VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, created_at DATETIME NOT NULL, INDEX IDX_C1F3FF8FA76ED395 (user_id), INDEX IDX_C1F3FF8F3481D195 (job_offer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE project__candidates ADD CONSTRAINT FK_C1F3FF8F3481D195 FOREIGN KEY (job_offer_id) REFERENCES project__job_offers (id)');
        $this->addSql('ALTER TABLE project__candidates ADD CONSTRAINT FK_C1F3FF8FA76ED395 FOREIGN KEY (user_id) REFERENCES user__users (id)');
        $this->addSql('DROP TABLE project__candidatures');
        $this->addSql('ALTER TABLE project__job_offers DROP is_active');
    }
}
