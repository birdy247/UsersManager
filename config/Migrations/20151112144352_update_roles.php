<?php
use Migrations\AbstractMigration;

class UpdateRoles extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $this->execute('insert into roles(id, role) values (1, "admin")'); 
    }
}
