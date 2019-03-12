<?php

declare(strict_types=1);

namespace App\Data\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190312223226 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE transazioni (id CHAR(36) NOT NULL COMMENT \'(DC2Type:IdTransazione)\', id_conto CHAR(36) NOT NULL COMMENT \'(DC2Type:IdConto)\', somma INT DEFAULT 0 NOT NULL, data_contabile DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', direzione VARCHAR(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conti (id CHAR(36) NOT NULL COMMENT \'(DC2Type:IdConto)\', titolare VARCHAR(255) NOT NULL, saldo INT DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conti_correnti_transazioni (conto_id CHAR(36) NOT NULL COMMENT \'(DC2Type:IdConto)\', transazione_id CHAR(36) NOT NULL COMMENT \'(DC2Type:IdTransazione)\', INDEX IDX_52967D78251976F (conto_id), UNIQUE INDEX UNIQ_52967D7760551AD (transazione_id), PRIMARY KEY(conto_id, transazione_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE conti_correnti_transazioni ADD CONSTRAINT FK_52967D78251976F FOREIGN KEY (conto_id) REFERENCES conti (id)');
        $this->addSql('ALTER TABLE conti_correnti_transazioni ADD CONSTRAINT FK_52967D7760551AD FOREIGN KEY (transazione_id) REFERENCES transazioni (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE conti_correnti_transazioni DROP FOREIGN KEY FK_52967D7760551AD');
        $this->addSql('ALTER TABLE conti_correnti_transazioni DROP FOREIGN KEY FK_52967D78251976F');
        $this->addSql('DROP TABLE transazioni');
        $this->addSql('DROP TABLE conti');
        $this->addSql('DROP TABLE conti_correnti_transazioni');
    }
}
