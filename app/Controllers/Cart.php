<?php

namespace App\Controllers;

use App\Classes\Route;
use CodeIgniter\HTTP\Request;
use CodeIgniter\HTTP\RequestInterface;
use App\Models\CartModel;
use App\Models\ProductModel;
use Config\Services;
use Config\Database;

class Cart extends BaseController
{
    public function index()
    {
        $db = Database::connect();
        $cartModel = new CartModel();

        $data = $db->table('cart')
                ->select("cart.id, cart.price, cart.quantity, cart.sub_total, products.name, products.id as product_id")
                ->join('products', 'products.id = cart.product_id')
                ->get()
                ->getResultObject();
        $no = nomor($this->request->getVar('page'), 5);

        $total = $db->table('cart')
                ->select('sum(sub_total) as total')
                ->get()
                ->getResultObject();

        $params = [
            "title" => "Data Produk",
            "data" => $data,
            "pager" => $cartModel->pager,
            "no" => $no,
            "total" => (count($total) > 0 )? $total[0]->total: 0
        ];

        return view('cart/index', $params);
        
    }

    public function save()
    {
        try {
            $type = $this->request->getPost('type');
            
            $productModel = new ProductModel();
            $cartModel = new CartModel();

            $productId = $this->request->getPost('id');
            $productById = $productModel->asObject()->where('id', $productId)->first();

            $cartCheck = $cartModel->asObject()->where('product_id', $productId)->first();


            if(is_null($cartCheck))
            {
                $insert = [
                    "product_id" => $productId,
                    "price" => $productById->price,
                    "quantity" => 1,
                    "sub_total" => $productById->price
                ];

                $cartModel->save($insert);
                $productModel->update($productId, [
                    "stock" => $productById->stock - 1
                ]);

            } else {

                if($type == "plus"){
                    $qty = $cartCheck->quantity + 1;
                    $subTotal = $productById->price * $qty;
                    if(($productById->stock - 1) < 0){
                        return $this->response->setJson([
                            "code" => "400",
                            "message" => "Stock tidak mencukupi",
                            "url" => url_to('cart.index')
                        ]);
                    }

                    $productModel->update($productId, [
                        "stock" => $productById->stock - 1
                    ]);
                } else {
                    $qty = $cartCheck->quantity - 1;
                    $subTotal = $productById->price * $qty;

                    if(($qty) < 1){
                        return $this->response->setJson([
                            "code" => "400",
                            "message" => "Quantity tidak boleh lebih kecil dari 1",
                            "url" => url_to('cart.index')
                        ]);
                    }

                    $productModel->update($productId, [
                        "stock" => $productById->stock + 1
                    ]);
                }

                $update = [
                    "price" => $productById->price,
                    "quantity" => $qty,
                    "sub_total" => $subTotal 
                ];

                $cartModel->update($cartCheck->id, $update);
            }

            return $this->response->setJson([
                "code" => "200",
                "message" => "Data berhasil disimpan !",
                "url" => url_to('cart.index')
            ]);

        } catch(\Exception $e)
        {
            return $this->response->setJson([
                "code" => "500",
                "message" => "Terjadi Kesalahan!",
                "url" => ""
            ]);
        }
    }

    public function discard()
    {
        try {
            $productId = $this->request->getPost('id');
            $productModel = new ProductModel();
            $cartModel = new CartModel();


            $product = $productModel->asObject()->where('id', $productId)->first();
            $cart = $cartModel->asObject()->where('product_id', $productId)->first();

            $updateProduct = [
                "stock" => $product->stock + $cart->quantity
            ];

            $productModel->update($productId, $updateProduct);

            $cartModel->where('product_id', $productId)->delete();

            return $this->response->setJson([
                "code" => "200",
                "message" => "Item berhasil dihapus !",
                "url" => url_to('cart.index')
            ]);

        } catch(\Exception $e)
        {
            return $this->response->setJson([
                "code" => "500",
                "message" => "Terjadi Kesalahan!",
                "url" => url_to('cart.index')
            ]);
        }
    }

    public function checkout()
    {
        try {
            $cartModel = new CartModel();

            $cart = $cartModel->asObject()->findAll();

            $cartModel->delete(array_column($cart, 'id'));

            return $this->response->setJson([
                "code" => "200",
                "message" => "Berhasil checkout !",
                "url" => url_to('cart.index')
            ]);

        } catch(\Exception $e)
        {
            return $this->response->setJson([
                "code" => "500",
                "message" => "Terjadi Kesalahan!",
                "url" => url_to('cart.index')
            ]);
        }
    }
}
