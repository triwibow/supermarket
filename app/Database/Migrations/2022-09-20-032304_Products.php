<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Products extends Migration
{
    public function up()
    {
        $this->forge->addField([
			'id'          => [
				'type'           => 'INT',
				'constraint'     => 5,
				'unsigned'       => true,
				'auto_increment' => true
			],
			'name'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '255'
            ],
            'price'       => [
				'type'           => 'DOUBLE',
            ],
            'stock'       => [
				'type'           => 'DOUBLE',
            ],
			'image'      => [
				'type'         	=> 'VARCHAR',
				'constraint'    => '255'
            ],
            'code'      => [
				'type'         	=> 'VARCHAR',
				'constraint'    => '255'
			],
			'serial_number'      => [
				'type'         	=> 'INT',
				'constraint'     => 5,
				'unsigned'       => true,
			]
		]);

		$this->forge->addKey('id', TRUE);
		$this->forge->createTable('products', TRUE);
    }

    public function down()
    {
        $this->forge->dropTable('products');
    }
}
