<?php

/**
 * Classe de widget de grid (Bootstrap)
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
    private $list = null;


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
     * Método que seta o array de resultados na grid
     * 
     * @param string $value
     * @return \Pilot_Widget_Grid
     */
    public function setList($value) {
        $this->list = $value;
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
    public function setColumn($display, $index, $options = "") {
        $this->columns[] = array('name' => $display, 'index' => $index, 'options' => $options);
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
        
        return $this->columns;
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
    
    
    public function getGrid($list = null) {
        
        try {
            
            if (is_null($list)) {
                $list = $this->list;
            }
            
            $columns = $this->getColumns();
            
            $grid = "
                    <div class='row-fluid sortable ui-sortable'>
                        <div class='box span12'>
                            <div class='box-header well' data-original-title>
                                <h2><i class='icon-th'></i> {$this->title}</h2>
                                <div class='box-icon'>
                                    <a href='#' class='btn btn-setting btn-round'><i class='icon-cog'></i></a>
                                    <a href='#' class='btn btn-minimize btn-round'><i class='icon-chevron-up'></i></a>
                                    <a href='#' class='btn btn-close btn-round'><i class='icon-remove'></i></a>
                                </div>
                            </div>
                            <div class='box-content'>
                                <table class='table table-striped table-bordered bootstrap-datatable datatable'>
                                    <thead>
                                        <tr>";
                                            foreach ($columns as $values) {
                                            $grid .= "<th>{$values['name']}</th>";
                                            }
                                        $grid .=
                                            "<th>Ações</th>
                                         </tr>
                                    </thead>

                                    <tbody>";
                                        foreach ($list as $key => $values) {
                                            $grid .=
                                            "<tr>";
                                                foreach ($columns as $column) {
                                                    $grid .= "<td {$column['options']}>{$values[$column['index']]}</td>";
                                                }
                                                $grid .= "
                                                <td class='center'>
                                                    <button class='btn btn-primary noty' data-noty-options='{'text':'Teste de informação','layout':'topCenter','type':'success'}'><i class='icon-bell icon-white'></i> Top Full Width</button>
                                                </td>
                                            </tr>";
                                        }
                                    $grid .=
                                    "</tbody>

                                </table>
                            </div>
                        </div>
                    </div>";
                                    
            return $grid;
            
        } catch (Exception $exc) {
            throw new Exception($exc->getMessage(), $exc->getCode());
        }
            
    }
    
    public function getGrid_() {
        
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
