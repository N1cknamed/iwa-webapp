<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240418153402 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE malfunction ADD datetime DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE malfunction ADD CONSTRAINT FK_DB39BE79B010593B FOREIGN KEY (station_name) REFERENCES station (name)');
        $this->addSql('CREATE INDEX IDX_DB39BE79B010593B ON malfunction (station_name)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FB010593B FOREIGN KEY (station_name) REFERENCES station (name)');
        $this->addSql('ALTER TABLE weather ADD CONSTRAINT FK_4CD0D36EDE08BD3C FOREIGN KEY (STN) REFERENCES station (name)');
        $this->addSql('CREATE INDEX IDX_4CD0D36EDE08BD3C ON weather (STN)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE malfunction DROP FOREIGN KEY FK_DB39BE79B010593B');
        $this->addSql('DROP INDEX IDX_DB39BE79B010593B ON malfunction');
        $this->addSql('ALTER TABLE malfunction DROP datetime');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FB010593B');
        $this->addSql('ALTER TABLE weather DROP FOREIGN KEY FK_4CD0D36EDE08BD3C');
        $this->addSql('DROP INDEX IDX_4CD0D36EDE08BD3C ON weather');
    }
}
