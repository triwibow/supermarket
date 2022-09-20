<?php

namespace App\Controllers;

use App\Classes\Route;
use CodeIgniter\HTTP\Request;
use CodeIgniter\HTTP\RequestInterface;
use App\Models\ProductModel;
use Config\Services;

class Home extends BaseController
{
    private function _generateCode($str, $serialNumber, $model)
    {
        try {
            
            $arrStr = explode(" ", $str);
            $code = "";

            if(count($arrStr) > 0)
            {
                if(count($arrStr) > 1)
                {
                    $code = strtoupper(substr($arrStr[0], 0, 1));
                    $code .= strtoupper(substr($arrStr[1], 0, 1)).sprintf("%03s", $serialNumber);
                } else {
                    $code = strtoupper(substr($arrStr[0], 0, 1)).strtoupper(substr($arrStr[0], 1, 1)).sprintf("%03s", $serialNumber);
                }
            }

            $check = $model->where('code', $code)->first();
            
            if(!is_null($check))
            {
                return $this->_generateCode($str, ($serialNumber + 1), $model);
            }

            return [
                "code" => $code,
                "serial_number" => $serialNumber
            ];
        } catch(\Exception $e)
        {
            
            return false;
        }
    }

    public function index()
    {
        $productModel = new ProductModel();

        $data = $productModel->asObject()
                ->where('stock >', 0)
                ->paginate(10);

        $no = nomor($this->request->getVar('page'), 10);

        $params = [
            "title" => "Data Produk",
            "data" => $data,
            "pager" => $productModel->pager,
            "no" => $no
        ];

        return view('home/index', $params);
        
    }

    public function add($id)
    {
        $productModel = new ProductModel();

        if(empty($id)){
            $data = $productModel;
        } else {    
            $data = $productModel->asObject()->find($id);
        }

        $params = [
            "title" => empty($id)? "Tambah Data":"Edit Data",
            "data" => $data
        ];

        return view('home/form', $params);
    }

    public function save()
    {
        try {

            $id = $this->request->getPost('id');
            $data = new ProductModel();
            $dataById = $data->asObject()->find($id);
            $reqFile = $this->request->getFiles('image');

            $fileRule = empty($id)? 'uploaded[image]|':'';
            
            $validation = $this->validate([
                'name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Nama produk belum di isi !'
                    ]
                ],
                'price' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Harga belum di isi !'
                    ]
                ],
                'stock' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Stock belum di isi !'
                    ]
                ],
                'foto' => [
                    'rules' => $fileRule.'mime_in[image,image/jpg,image/jpeg,image/gif,image/png]|max_size[image,2048]',
                    'errors' => [
                        'uploaded' => 'Harus Ada File yang diupload',
                        'mime_in' => 'File Extention Harus Berupa jpg,jpeg,gif,png',
                        'max_size' => 'Ukuran File Maksimal 2 MB'
                    ]
     
                ]
            ]);
            
            if(!$validation)
            {
                foreach($this->validator->getErrors() as $key => $item){
                    return $this->response->setJson([
                        "code" => "404",
                        "message" => $item
                    ]);
                }
            }

            $maxSerialNumber = $data->asObject()->select('max(serial_number) as serial_number')->first();
            $currentSerialNumber = (is_null($maxSerialNumber->serial_number))? 1:$maxSerialNumber->serial_number + 1; 
            $code = $this->_generateCode($this->request->getPost('name'), $currentSerialNumber, $data);

            if(!$code) {
                return $this->response->setJson([
                    "code" => "500",
                    "message" => "Terjadi kesalahan generate code "
                ]); 
            }


            $fileName = "";
            $fileToSave = "";
            $destinationPath = "uploads/image/";

            if(empty($id)){
                $fileName = $code["code"].".".$reqFile['image']->getClientExtension();
                $fileToSave = $destinationPath.$fileName;
                $reqFile['image']->move('uploads/image', $fileName);
            } else {
                if($reqFile['image']->getError() == 0){
                    $fileName = $code["code"].".".$reqFile['image']->getClientExtension();
                    $fileToSave = $destinationPath.$fileName;
                    $reqFile['image']->move('uploads/image', $fileName);
                } else {
                    $fileToSave = $dataById->foto;
                }
            }
            

            $insert = [
                "id" => (empty($id))? null: $id,
                "name" => $this->request->getPost('name'),
                "price" => $this->request->getPost('price'),
                "stock" => $this->request->getPost('stock'),
                "code" => (empty($id))? $code['code']: $dataById->code,
                "serial_number" => (empty($id))? $code['serial_number']:$dataById->serial_number,
                "image" => $fileToSave

            ];

            $data->save($insert);
            
            
            return $this->response->setJson([
                "code" => "200",
                "message" => "Data berhasil disimpan !"
            ]);
        } catch(\Exception $e)
        {
            return $this->response->setJson([
                "code" => "500",
                "message" => "Terjadi Kesalahan!"
            ]);
        }
    }

}
