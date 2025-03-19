<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250318154335 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE gorest_user (id INT AUTO_INCREMENT NOT NULL, gorest_id INT, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, gender VARCHAR(10) NOT NULL, status VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE INDEX idx_gorest_user_name ON gorest_user (name)');
        $this->addSql('CREATE INDEX idx_gorest_user_gorest_id ON gorest_user (gorest_id)');
        $this->addSql('CREATE INDEX idx_gorest_user_email ON gorest_user (email)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX idx_gorest_user_name ON gorest_user');
        $this->addSql('DROP INDEX idx_gorest_user_gorest_id ON gorest_user');
        $this->addSql('DROP INDEX idx_gorest_user_email ON gorest_user');
        $this->addSql('DROP TABLE gorest_user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
