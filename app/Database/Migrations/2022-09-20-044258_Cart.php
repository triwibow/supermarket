<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Cart extends Migration
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
			'product_id'       => [
				'type'           => 'INT',
				'constraint'     => 5,
				'unsigned'       => true,
            ],
			'price'       => [
				'type'           => 'DOUBLE',
            ],
            'quantity'       => [
				'type'           => 'DOUBLE',
            ],
            'sub_total'       => [
				'type'           => 'DOUBLE',
            ]
		]);

		$this->forge->addKey('id', TRUE);
		$this->forge->createTable('cart', TRUE);
    }

    public function down()
    {
        $this->forge->dropTable('cart');
    }
}
