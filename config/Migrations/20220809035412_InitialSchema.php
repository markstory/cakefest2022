<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class InitialSchema extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('users')
            ->addColumn('username', 'string')
            ->addColumn('password', 'string')
            ->addColumn('created', 'datetime')
            ->addColumn('modified', 'datetime');
        $table->create();

        $table = $this->table('calendar_items')
            ->addColumn('user_id', 'integer', [
                'null' => false,
            ])
            ->addColumn('title', 'string')
            ->addColumn('description', 'text')
            ->addColumn('start_time', 'datetime', [
                'null' => true,
            ])
            ->addColumn('end_time', 'datetime', [
                'null' => true,
            ])
            ->addColumn('start_date', 'datetime', [
                'null' => true,
            ])
            ->addColumn('end_date', 'datetime', [
                'null' => true,
            ]);
        $table->create();
    }
}
