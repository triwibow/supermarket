<?php

namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model
{
    protected $table      = 'cart';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'product_id', 'price','sub_total', 'quantity'
    ];
}