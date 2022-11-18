<?php
declare (strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class PostTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('posts');
        $table->addColumn('createdBy', 'integer', ['null' => false])
            ->addColumn('title', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('content', 'text', ['null' => false])
            ->addColumn('image', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('createdAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addColumn('updatedAt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
        ;
        // @TODO fix it
        // ->addForeignKey('createdBy', 'users', ['id'], ['constraint' => 'post_user_id']);
        $table->save();
    }
}
