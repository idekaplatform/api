<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190630103136 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE project__skills (job_offer_id INT NOT NULL, skill_id INT NOT NULL, level INT NOT NULL, INDEX IDX_BA1CFD963481D195 (job_offer_id), INDEX IDX_BA1CFD965585C142 (skill_id), PRIMARY KEY(job_offer_id, skill_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user__skills (skill_id INT NOT NULL, user_id INT NOT NULL, self_evaluation INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_740FEFF95585C142 (skill_id), INDEX IDX_740FEFF9A76ED395 (user_id), PRIMARY KEY(skill_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE skills (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(80) NOT NULL, type VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project__skills ADD CONSTRAINT FK_BA1CFD963481D195 FOREIGN KEY (job_offer_id) REFERENCES project__job_offers (id)');
        $this->addSql('ALTER TABLE project__skills ADD CONSTRAINT FK_BA1CFD965585C142 FOREIGN KEY (skill_id) REFERENCES skills (id)');
        $this->addSql('ALTER TABLE user__skills ADD CONSTRAINT FK_740FEFF95585C142 FOREIGN KEY (skill_id) REFERENCES skills (id)');
        $this->addSql('ALTER TABLE user__skills ADD CONSTRAINT FK_740FEFF9A76ED395 FOREIGN KEY (user_id) REFERENCES user__users (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project__skills DROP FOREIGN KEY FK_BA1CFD965585C142');
        $this->addSql('ALTER TABLE user__skills DROP FOREIGN KEY FK_740FEFF95585C142');
        $this->addSql('DROP TABLE project__skills');
        $this->addSql('DROP TABLE user__skills');
        $this->addSql('DROP TABLE skills');
    }
}
