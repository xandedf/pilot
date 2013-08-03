<?php

/**
 * Simple class for files upload
 *
 * @author Rafael Wendel Pinheiro
 * @version 1.0
 * @link https://github.com/rafaelwendel/UploadHelper
 * @author Alexandre Gomes
 * @version 1.1
 */
class uploadHelper extends Pilot_Lib {

    /**
     * The file to be sent
     * @access protected
     * @var array
     */
    protected $_file;

    /**
     * The extension of file
     * @access protected
     * @var String
     */
    protected $_ext;

    /**
     * The folder to receive the file
     * @access protected
     * @var String
     */
    protected $_uploads_folder;

    /**
     * The uploaded file path
     * @access protected
     * @var String
     */
    protected $_file_path;

    /**
     * The new name of file
     * @access protected
     * @var String
     */
    protected $_file_name = null;

    /**
     * The allowed extensions in the upload
     * @access protected
     * @var array
     */
    protected $_allowed_exts = array();

    /**
     * The max size of file (in MegaBytes)
     * @access protected
     * @var Double
     */
    protected $_max_size = 2;

    /**
     * Overwrite file with same name?
     * @access protected
     * @var Boolean
     */
    protected $_overwrite = true;

    /**
     * The error message
     * @access protected
     * @var String
     */
    protected $_error;

    /**
     * The default messages
     * @access protected
     * @var array
     */
    protected $_default_messages = array();

    /**
     * The language of messages. (English (en) or Portuguese (pt) 
     * @access protected
     * @var String
     */
    protected $_language = 'pt';

    /**
     * Constructor method. You can define the file, uploads folder, new file name and the language. Define too the default messages
     * @access public
     * @param $file File The file to be upload
     * @param $uploads_folder String The path of the receive folder
     * @param $file_name String The new name
     * @return Void
     */
    public function __construct($file = null, $uploads_folder = null, $file_name = null, $language = 'pt') {
        if (!is_null($file)) {
            $this->set_file($file);
        }
        
        $this->set_uploads_folder($uploads_folder);
        
        if (!is_null($file_name)) {
            $this->set_file_name($file_name);
        }

        $this->set_language($language);
        $this->set_default_messages();
    }

    /**
     * Set the default messages
     * @access protected
     * @return uploadHelper
     */
    protected function set_default_messages() {
        $this->_default_messages['en'] = array(
            '1' => 'File is not set',
            '2' => 'Uploads folder is not set or no exists',
            '3' => 'Files of type {{exts}} are not allowed',
            '4' => 'The file size is larger than {{max_size}}MB',
            '5' => 'Error when uploading'
        );

        $this->_default_messages['pt'] = array(
            '1' => 'Arquivo não setado',
            '2' => 'Pasta de uploads não definida ou não existe',
            '3' => 'Arquivos do tipo {{exts}} não são permitidos',
            '4' => 'O tamanho do arquivo é maior que {{max_size}}MB',
            '5' => 'Erro ao fazer upload'
        );
        
        return $this;
    }

    /**
     * Set the language messages
     * @access public
     * @param $language String The language
     * @return uploadHelper
     */
    public function set_language($language) {
        $this->_language = ($language == 'en' || $language == 'pt' ? $language : 'en');
        
        return $this;
    }

    /**
     * Set a file
     * @access public
     * @param $file File The file to be upload
     * @return uploadHelper
     */
    public function set_file($file) {
        $this->_file = $file;
        $this->set_ext($file);
        
        return $this;
    }

    /**
     * Set the extension of file
     * @access protected
     * @param $file File The file will be sent
     * @return uploadHelper
     */
    protected function set_ext($file) {
        
        $arr = explode('.', $file['name']);
        $ext = end($arr);
        $this->_ext = strtolower($ext);
        
        return $this;
    }

    /**
     * Set the folder to receive the file
     * @access public
     * @param $uploads_folder String The path of the receive folder
     * @return uploadHelper
     */
    public function set_uploads_folder($uploads_folder = null) {
        
        if (is_null($uploads_folder)) {
            
            //Path do arquivo
            $uploadDir = realpath(PILOT_ROOT) . DS . "var";

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir);
                chmod($uploadDir, 0777);
            }
            $uploadDir = $uploadDir . DS . 'uploads';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir);
                chmod($uploadDir, 0777);
            }
            
            $uploads_folder = $uploadDir;
            
        }
        
        if (substr($uploads_folder, -1) == '/') {
            $this->_uploads_folder = $uploads_folder;
        } else {
            $this->_uploads_folder = $uploads_folder . '/';
        }
        
        return $this;
    }

    /**
     * Set the new name of the file
     * @access public
     * @param $file_name String The new name
     * @return uploadHelper
     */
    public function set_file_name($file_name) {
        $this->_file_name = $file_name;
        
        return $this;
    }

    /**
     * Set the max size of file
     * @access public
     * @param $max_size Double The max size of file
     * @return uploadHelper
     */
    public function set_max_size($max_size) {
        $this->_max_size = $max_size;
        
        return $this;
    }

    /**
     * Set the allowed extensions in the upload
     * @access public
     * @param $file_name String The new name
     * @return Void
     */
    public function set_allowed_exts($allowed_exts) {
        if (is_array($allowed_exts)) {
            $this->_allowed_exts = $allowed_exts;
        }
        if (is_string($allowed_exts)) {
            $this->_allowed_exts[] = $allowed_exts;
        }
        
        return $this;
    }

    /**
     * Overwrite file with same name? (true or false)
     * @access public
     * @param $param Boolean Yes(true) or no(false)
     * @return Void
     */
    public function set_overwrite($param) {
        $this->_overwrite = (bool) $param;
        
        return $this;
    }

    /**
     * Set a error message
     * @access protected
     * @param $error_num String The error number (1, 2, 3 ou 4)
     * @return Void
     */
    protected function set_error($error_num) {
        $this->_error = $this->_default_messages[$this->_language][$error_num];
        
        return $this;
    }

    /**
     * Get the error message
     * @access public
     * @return String The error message
     */
    public function get_error() {
        $this->_error = str_replace('{{exts}}', $this->_ext, $this->_error);
        $this->_error = str_replace('{{max_size}}', $this->_max_size, $this->_error);

        return $this->_error;
    }

    /**
     * Get the uploaded file path
     * @access public
     * @return String The uploaded file path
     */
    public function get_file_path() {
        return $this->_file_path;
    }

    /**
     * Keep the file with the same name
     * @access protected
     * @return Void
     */
    protected function some_name() {
        $tmp_name = explode('.', $this->_file['name']);
        unset($tmp_name[count($tmp_name) - 1]);

        $this->_file_name = implode('.', $tmp_name);
        
        return $this;
    }

    /**
     * Checks whether to overwrite files with the same name. If not, creates name incremented ($name, $name_1, $name_2, $name_n)
     * @access protected
     * @return Void
     */
    protected function verify_overwrite() {
        if (!$this->_overwrite) {
            $tmp_name = $this->_file_name;
            $x = 1;

            while (file_exists($this->_uploads_folder . $this->_file_name . '.' . $this->_ext)) {
                $this->_file_name = $tmp_name . '_' . $x;
                $x++;
            }
        }
        
        return $this;
    }

    /**
     * Validates requirements for uploading
     * @access protected
     * @return Boolean True if valid
     */
    protected function is_valid() {
        if (empty($this->_file['name'])) {
            $this->set_error(1);
            return false;
        }
        if (empty($this->_uploads_folder) || !file_exists($this->_uploads_folder)) {
            $this->set_error(2);
            return false;
        }
        if (!in_array($this->_ext, $this->_allowed_exts)) {
            $this->set_error(3);
            return false;
        }
        if (!$this->validate_size()) {
            $this->set_error(4);
            return false;
        }
        return true;
    }

    /**
     * Validate the size of file
     * @access protected
     * @return Boolean True if valid
     */
    protected function validate_size() {
        $file_size = $this->_file['size'];

        /* Convert bytes to megabytes */
        $file_size = ($file_size / 1024) / 1024;

        if ($file_size > $this->_max_size) {
            return false;
        }

        return true;
    }

    /**
     * Upload the file
     * @access public
     * @return Boolean True if file has been uploaded
     */
    public function upload_file() {
        if (!$this->is_valid()) {
            return false;
        }

        if (!isset($this->_file_name)) {
            $this->some_name();
        }

        $this->verify_overwrite();

        if (move_uploaded_file($this->_file['tmp_name'], $this->_uploads_folder . $this->_file_name . '.' . $this->_ext)) {
            $this->_file_path = $this->_uploads_folder . $this->_file_name . '.' . $this->_ext;
            return true;
        } else {
            $this->set_error(5);
            return false;
        }
    }

}