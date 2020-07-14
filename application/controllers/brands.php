<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Brands extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        if (! $this->user) {
            redirect('login');
        }
    }

    public function index($band=NULL)
    {
        $this->view_data['band'] = $band;
        $this->view_data['brands'] = Brand::all();
        $this->content_view = 'brand/view';
    }

    public function add()
    {
        date_default_timezone_set($this->setting->timezone);
        $date = date("Y-m-d H:i:s");
        //$_POST['date'] = $date;
        $user = Brand::create($_POST);
        redirect("brands", "refresh");
    }

    public function edit($id = FALSE)
    {
        if ($_POST) {
            $brand = Brand::find($id);
            $brand->update_attributes($_POST);
            redirect("brands", "refresh");
        } else {
            $this->view_data['brands'] = Brand::find($id);
            $this->content_view = 'brand/edit';
        }
    }

    public function delete($id)
    {
        $brand = Brand::find($id);
        $brand->delete();
        redirect("brands", "refresh");
    }
    public function importcsv()
    {
        $config['upload_path'] = './files/products';
        $config['allowed_types'] = 'csv';
        $config['overwrite'] = TRUE;
        $config['max_size'] = '2000';
        
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ($this->upload->do_upload('user_file_import')) {
            $data = array(
                'upload_data' => $this->upload->data()
            );
            $file = $data['upload_data']['file_name'];

            $fileopen = fopen('files/products/' . $file, "r");
            if ($fileopen) {
                while (($row = fgetcsv($fileopen, 2075, ",")) !== FALSE) {
                    $filearray[] = $row;
                }
                fclose($fileopen);
            }
            
            array_shift($filearray);
            //print_r($filearray);

            $fields = array(
                'name'
            );

            $final = array();
            foreach ($filearray as $key => $value) {
                $products[] = array_combine($fields, $value);
            }
            //print_r($products);
            date_default_timezone_set($this->setting->timezone);
            $date = date("Y-m-d H:i:s");
            $band=-1;
            $i=0;

              foreach ($products as $prdct) {
                $i++;
                $name=$prdct['name'];
               // if(strlen($name)==0)
                 //   $name="nada";
                
                $item = Brand::find('first', array('conditions' => array('name = ? ', $name)));
                if(!$item)
                {                 
                  $data = array(
                      "name" => $name,
                      "date" => $date
                  );
                  //print_r($data);
                  Brand::create($data);
                }                  
              }

              unlink('./files/products/' . $file);
              redirect('brands/index/'.$band);

        }
        //else die("error aqui".$this->upload->display_errors());
        redirect('brands');
    }
}
