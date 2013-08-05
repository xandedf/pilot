<?php

class indexController extends controllerGrid {
    
    public function init() {
        parent::init();
        
        $this->setTitle('Página de Exemplos');
        $this->setFooter('footer');
        $this->setHeader('header');
    }


    public function indexAction() {

        $this->setJS('teste.js');
        
        $customer = new customerModel();
        
        $dados['list'] = $customer->find()->getArrayResult();
        
        $this->loadTemplate('index', $dados);
        
    }
    
    public function listAction() {
        
        $this->setCSS('list.css');
        
        $customer = new customerModel();
        
        $dados['list'] = $customer->find()->getArrayResult();
        
        $this->loadTemplate('list', $dados);
        
    }


    public function insertAction() {
        
        $customer = new customerModel();
        
        $customer->setValue('name', $this->getParams('nome'));
        $customer->setValue('email','teste@teste.com.br');
        $customer->setValue('telefone',$this->getParams('telefone'));
        
        $dados['id'] = $customer->insert()->getReturnId();
        $dados['list'] = $customer->find()->getArrayResult();
        
        $this->loadTemplate('index', $dados);
        
    }
    
    public function gridAction() {
        
        $customer = new customerModel();
        
        $dados['list'] = $customer->find()->getArrayResult();
        
        $grid = new Grid('testegrid');
        $grid->setColumn('ID', 'id')
             ->setColumn('Nome', 'name', "class='center'")
             ->setColumn('E-Mail', 'email', "class='center'")
             ->setColumn('Telefone', 'telefone', "class='center'")
             ->setList($dados['list'])
             ->setTitle('Grid Teste');
        $grid = $grid->getGrid();
        
        $dados['grid'] = $grid;
        
        $this->loadTemplate('grid', $dados);
        
    }
    
}
