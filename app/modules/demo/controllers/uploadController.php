<?php

class uploadController extends Pilot_Core_Controller {
    
    public function init() {
        parent::init();
        $this->setTitle('Página de Upload');
        $this->setCSS('padrao.css');
        $this->setCSS('footer/footer.css');
        $this->setFooter('footer');
        $this->setHeader('header');
        $this->setJS('teste.js');
    }
 
    public function indexAction() {
        $this->loadTemplate('index');
    }
    
    public function uploadAction() {
        $this->loadTemplate('upload');
    }
    
    public function uploadPostAction() {
        
        $this->setTitle('teste upload');
        
        $fileUpload = $_FILES['files'];
        $file = pathinfo($fileUpload['name']);
        $allowed = array('txt', 'xlsx');
        
        $upload = new uploadHelper();
        $upload->set_max_size(20)
               ->set_allowed_exts($allowed)
               ->set_file_name($file['filename'].date('_YmdHis'))
               ->set_file($fileUpload);
        
        if(!$upload->upload_file()) {
            $dados['result'] = $upload->get_error();
        } else {
            $dados['result'] = 'Arquivo gravado no ' . $upload->get_file_path();
        }
        
        $dados['upload'] = $upload;
        $dados['file'] = $file;
        
        $this->loadTemplate('uploadPost', $dados);
        
    }
    
}