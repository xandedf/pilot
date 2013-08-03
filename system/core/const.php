<?php
/**
 * Classe base de constantes
 *
 * @package     Pilot
 * @copyright   Copyright (c) 2013 ARG Softwares (http://www.argsoftwares.com.br)  
 * @license     Commercial
 * @author      Alexandre Gomes <alexandre.gomes@discover.com.br>
 */

class Pilot_Const {
    
    const LOG_ERROR = 1;
    const LOG_WARNING = 2;
    const LOG_INFORMATION = 3;
    
    const type_like = 'LIKE';
    const type_igual = '=';
    const type_diferente = '<>';
    const type_menorigual = '<=';
    const type_maiorigual = '>=';
    const type_between = 'BETWEEN';
    const type_conjunto = 'IN()';
    
    const type_compar_and = 'AND';
    const type_compar_or = 'OR';
    
    const type_inner_join = 'INNER JOIN';
    const type_left_join = 'LEFT JOIN';
    const type_right_join = 'RIGHT JOIN';
    const type_cross_join = 'CROSS JOIN';
    
    const MSG_ERRO_EXISTE_FOOTER = 'Arquivo de footer não definido.';
    const MSG_ERRO_EXISTE_FOOTER_COD = 1;
    const MSG_ERRO_EXISTE_HEADER = 'Arquivo de header não definido.';
    const MSG_ERRO_EXISTE_HEADER_COD = 2;
    const MSG_ERRO_FILE_EXISTE = "Arquivo %s não existe.";
    const MSG_ERRO_FILE_TEMPLATE_COD = 3;
    const MSG_ERRO_CONNECTION = "Não foi possível conectar: %s.";
    const MSG_ERRO_CONNECTION_COD = 4;
    const MSG_ERRO_MAILER = "Mailer Error: %s";
    const MSG_ERRO_MAILER_COD = 5;
    const MSG_ERRO_SMTP = "SMPT não prenchido.";
    const MSG_ERRO_SMTP_COD = 6;
    const MSG_ERRO_COLUMNS_GRID = 'Não existem colunas setadas para a grid %s';
    const MSG_ERRO_COLUMNS_GRID_COD = 7;
    const MSG_ERRO_BUTTONS_GRID = 'Não existem buttons setadas para a grid %s';
    const MSG_ERRO_BUTTONS_GRID_COD = 8;
    const MSG_ERRO_GRIDNAME = 'Nome da Grid não foi setado';
    const MSG_ERRO_GRIDNAME_COD = 9;
    
    
}
