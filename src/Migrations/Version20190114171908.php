<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190114171908 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE bundle (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bundles_products (bundle_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_845BC30BF1FAD9D3 (bundle_id), UNIQUE INDEX UNIQ_845BC30B4584665A (product_id), PRIMARY KEY(bundle_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bundle ADD CONSTRAINT FK_A57B32FDBF396750 FOREIGN KEY (id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bundles_products ADD CONSTRAINT FK_845BC30BF1FAD9D3 FOREIGN KEY (bundle_id) REFERENCES bundle (id)');
        $this->addSql('ALTER TABLE bundles_products ADD CONSTRAINT FK_845BC30B4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product ADD discr VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bundles_products DROP FOREIGN KEY FK_845BC30BF1FAD9D3');
        $this->addSql('DROP TABLE bundle');
        $this->addSql('DROP TABLE bundles_products');
        $this->addSql('ALTER TABLE product DROP discr');
    }
}
