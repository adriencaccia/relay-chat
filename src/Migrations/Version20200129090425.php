<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200129090425 extends AbstractMigration
{
    private $users;
    private $messages;

    public function getDescription(): string
    {
        return 'Add relayId to user and message';
    }

    public function preUp(Schema $schema): void
    {
        $this->users = $this->connection->fetchAll('SELECT * FROM user');
        $this->messages = $this->connection->fetchAll('SELECT * FROM message');
    }

    public function up(Schema $schema): void
    {
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD relay_id VARCHAR(13) NOT NULL');
        foreach ($this->users as $user) {
            $this->addSql(
                'UPDATE user SET relay_id = :relay_id WHERE id = :id',
                ['relay_id' => uniqid(), 'id' => $user['id']]
            );
        }
        $this->addSql('ALTER TABLE message ADD relay_id VARCHAR(13) NOT NULL');
        foreach ($this->messages as $message) {
            $this->addSql(
                'UPDATE message SET relay_id = :relay_id WHERE id = :id',
                ['relay_id' => uniqid(), 'id' => $message['id']]
            );
        }
    }

    public function down(Schema $schema): void
    {
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE message DROP relay_id');
        $this->addSql('ALTER TABLE user DROP relay_id');
    }
}
