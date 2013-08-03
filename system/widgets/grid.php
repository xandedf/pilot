<?php

/**
 * Classe de widget de grid (FlexiGrid)
 *
 * @package     Pilot
 * @author      Alexandre Gomes <alexandre.gomes@discover.com.br>
 * @copyright   Copyright (c) 2013 ARG Softwares (http://www.argsoftwares.com.br)  
 * @license     Commercial
 */
class Grid extends Pilot_Lib {
    
    const GRID_BUTTON_EDIT = 'edit';
    const GRID_BUTTON_DELETE = 'delete';
    const GRID_BUTTON_ADD = 'add';
    
    private $flexigrid = '';
    private $url;
    private $dataType = 'json';
    private $columns = array();
    private $buttons = array();
    private $searchItens = array();
    private $buttonSeparator = true;
    private $sortname = '';
    private $sortorder = 'asc';
    private $usepager = true;
    private $title = '';
    private $userp = true;
    private $rp = 15;
    private $resizable = false;
    private $width = 700;
    private $height = 370;
    private $singleSelect = false;


    public function __construct($gridName = null) {
        parent::__construct();
        
        if (!is_null($gridName)) {
            $this->flexigrid = $gridName;
        }
    }
    
    /**
     * Método que seta o nome da grid
     * 
     * @param string $value
     * @return \Pilot_Widget_Grid
     */
    public function setGridName($value) {
        $this->flexigrid = $value;
        return $this;
    }
    
    /**
     * Método que retorna o nome da grid
     * @return string
     */
    public function getGridName() {
        if ($this->flexigrid == '') {
            throw new Exception(self::___(self::MSG_ERRO_GRIDNAME), self::MSG_ERRO_GRIDNAME_COD);
        }
        return $this->flexigrid;
    }
    
    /**
     * Método que seta o Título da Grid
     * 
     * @param string $value
     * @return \Pilot_Widget_Grid
     */
    public function setTitle($value) {
        $this->title = $value;
        return $this;
    }
    
    /**
     * Método que seta se a Grid poderá ser redimensionada
     * ou não
     * 
     * @param boolean $value
     * @return \Pilot_Widget_Grid
     */
    public function setResize($value) {
        $this->resizable = $value;
        return $this;
    }
    
    /**
     * Método que seta o tamanho da Grid
     * 
     * @param int $width
     * @param int $height
     * @return \Pilot_Widget_Grid
     */
    public function setSize($width, $height) {
        $this->width = $width;
        $this->height = $height;
        return $this;
    }
    
    /**
     * Método que seta se a grid poderá ou não ter
     * várias linhas selecionadas
     * 
     * @param boolean $value
     * @return \Pilot_Widget_Grid
     */
    public function setSingleSelect($value) {
        $this->singleSelect = $value;
        return $this;
    }
    
    /**
     * Método que seta a URL que trará os dados para a grid
     * 
     * @param string $value
     * @return \Pilot_Widget_Grid
     */
    public function setUrl($value) {
        $this->url = $value;
        return $this;
    }
    
    /**
     * Método que seta o tipo de dado que a Grid irá trabalhar
     * 
     * @param string $value
     * @return \Pilot_Widget_Grid
     */
    public function setDataType($value = 'json') {
        $this->dataType = $value;
        return $this;
    }
    
    /**
     * Método que seta se a existirá o separador de buttons
     * 
     * @param boolean $value
     * @return \Pilot_Widget_Grid
     */
    public function setButtonSeparator($value) {
        $this->buttonSeparator = $value;
        return $this;
    }
    
    /**
     * Método que inclui uma coluna na Grid
     * 
     * @param string $display
     * @param string $name
     * @param int $width
     * @param boolean $sortable
     * @param string $align
     * @return \Pilot_Widget_Grid
     */
    public function setColumn($display, $name, $width, $sortable = true, $align = 'left') {
        $this->columns[] = "{display: '{$display}', name: '{$name}', width: {$width}, sortable: {$sortable}, align: '$align'}";
        return $this;
    }
    
    /**
     * Método que inclui um botão na Grid
     * 
     * @param string $name
     * @param string $bclass
     * @param string $onpress
     * @return \Pilot_Widget_Grid
     */
    public function setButton($name, $bclass, $onpress) {
        $this->buttons[] = "{name: '{$name}', bclass: '{$bclass}', onpress: {$onpress}}";
        return $this;
    }
    
    /**
     * Método que inclui um ítem de Seach na Grid
     * 
     * @param string $display
     * @param string $name
     * @param string $isdefault
     * @return \Pilot_Widget_Grid
     */
    public function setSearchItens($display, $name, $isdefault = false) {
        $this->searchItens[] = "{display: '{$display}', name: '{$name}', isdefault: true}";
        return $this;
    }
    
    /**
     * Método que seta o Sortname da Grid
     * 
     * @param string $value
     * @return \Pilot_Widget_Grid
     */
    public function setSortname($value) {
        $this->sortname = $value;
        return $this;
    }
    
    /**
     * Método que seta o tipo de ordenamento da grid
     * 
     * @param string $value
     * @return \Pilot_Widget_Grid
     */
    public function setSortorder($value) {
        $this->sortorder = $value;
        return $this;
    }
    
    /**
     * Método que seta se a Grid exibirá a paginação
     * 
     * @param boolean $value
     * @return \Pilot_Widget_Grid
     */
    public function setUsepager($value) {
        $this->usepager = $value;
        return $this;
    }
    
    /**
     * Método que seta se a Grid usará o número de registos
     * por página
     * 
     * @param boolean $value
     * @return \Pilot_Widget_Grid
     */
    public function setUserp($value) {
        $this->userp = $value;
        return $this;
    }
    
    /**
     * Método que seta o número de registros por página
     * 
     * @param int $value
     * @return \Pilot_Widget_Grid
     */
    public function setRP($value) {
        $this->rp = $value;
        return $this;
    }
    
    /**
     * Método que retorna a string de colunas montada
     * 
     * @return string
     * @throws Exception
     */
    public function getColumns() {
        
        if (count($this->columns) == 0) {
            throw new Exception(self::___(self::MSG_ERRO_COLUMNS_GRID, $this->flexigrid), self::MSG_ERRO_COLUMNS_GRID_COD);
        }
        
        $columns = '';
        foreach ($this->columns as $value) {
            $columns .= $value . ',';
        }
        
        $columns = substr($columns,0,-1);
        $columns = "colModel: [ {$columns} ],";
        
        return $columns;
        
    }
    
    /**
     * Método que retorna a string com os botões
     * 
     * @return string
     */
    public function getButtons() {
        if (count($this->buttons) == 0) {
            return '';
        }
        
        $buttons = '';
        foreach ($this->buttons as $value) {
            $buttons .= $value . ',';
        }
        
        $buttons = substr($buttons,0,-1);
        $buttons = "buttons: [ {$buttons} ],";
        
        return $buttons;
    }
    
    /**
     * Método que retorna a string com os seach itens para a Grid
     * 
     * @return string
     */
    public function getSearchItens() {
        if (count($this->searchItens) == 0) {
            return '';
        }
        
        $search = '';
        foreach ($this->searchItens as $value) {
            $search .= $value . ',';
        }
        
        $search = substr($search,0,-1);
        $search = "searchitems: [ {$search} ],";
        
        return $search;
    }
    
    public function getGrid() {
        
        try {
            
            $columns = $this->getColumns();
            $buttons = $this->getButtons();
            $searchs = $this->getSearchItens();
            
            $grid = "
                <script type='text/javascript'>
                $(function() {
                $(\".{$this->getGridName()}\").flexigrid(
                    {
                        url: '{$this->url}',
                        dataType: '{$this->dataType}',
                        {$columns}
                        {$buttons}
                        {$searchs}
                        sortname: '{$this->sortname}',
                        sortorder: '{$this->sortorder}',
                        usepager: {$this->usepager},
                        title: '{$this->title}',
                        useRp: {$this->userp},
                        rp: {$this->rp},
                        showTableToggleBtn: true,
                        resizable: false,
                        width : {$this->width},
                        height : {$this->height},
                        singleSelect: true
                    });
                });
                </script>
            ";
            
            return $grid;
            
        } catch (Exception $exc) {
            throw new Exception($exc->getMessage(), $exc->getCode());
        }
            
    }
}
