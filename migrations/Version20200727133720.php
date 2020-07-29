<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200727133720 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'create db tables';
    }

    public function up(Schema $schema) : void
    {
       $this->addSql("CREATE TABLE symfony_demo_user (
            id INTEGER not null
            primary key,
            full_name VARCHAR(255) not null,
            username VARCHAR(255) not null,
            email VARCHAR(255) not null,
            password VARCHAR(255) not null,
            roles CLOB not null);"
       );
        $this->addSql("create unique index UNIQ_8FB094A1E7927C74 on symfony_demo_user (email);");
        $this->addSql("create unique index UNIQ_8FB094A1F85E0677 on symfony_demo_user (username);");

        $table = $schema->createTable('country');
        $table->addColumn('id', 'integer', [
            'autoincrement' => true,
        ]);
        $table->addColumn('name', 'string');

        $table = $schema->createTable('city');
        $table->addColumn('id', 'integer', [
            'autoincrement' => true,
        ]);
        $table->addColumn('name', 'string');


        $table = $schema->createTable('addressees');
        $table->addColumn('id', 'integer', [
            'autoincrement' => true,
        ]);
        $table->addColumn('firstname', 'string');
        $table->addColumn('lastname', 'string');
        $table->addColumn('country_id', 'integer')->setNotnull(false);
        $table->addColumn('city_id', 'integer')->setNotnull(false);
        $table->addColumn('street', 'string');
        $table->addColumn('house', 'string');
        $table->addColumn('zip', 'string');
        $table->addColumn('phone_number', 'string');
        $table->addColumn('email', 'string');
        $table->addColumn('birthday', 'datetime');
        $table->addColumn('pictureurl', 'string')->setNotnull(false);
    }

    public function down(Schema $schema) : void
    {
        $schema->dropTable('addressees');
        $schema->dropTable('city');
        $schema->dropTable('country');
        $schema->dropTable('symfony_demo_user');
    }
}
