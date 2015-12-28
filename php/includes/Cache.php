<?php
/**
 * Implementa um sistema básico de cache
 *
 * @author Gutemberg Dantas <gti5tecnologias@outlook.com>
*/
class Cache
{
    /** Diretório de Armazenamento do Cache */
    private $_folderCache;
    /** Nome do arquivo de cache, sem extensão */
    private $_fileCache;
    /** Tempo para expirar, em segundos */
    private $_timeout;

    /**
     *
     * @param String $folder
    */
    public function __construct($folder = "")
    {
        /*Arquivo expira em 7 dias 'padrão'
        * 3600se => 1h | 7 dias (1 semana) => 168h * 3 semanas = 504h
        */
        $this->_timeout = 3600 * 168;

        //Diretório inicial padrão
        $this->setStandardFolder($folder);
    }

    /**
     * Nome do arquivo de cache
     * @access public
     * @param String $file nome do arquivo de cache
     * @param String $prefix prefixo ao nome do arquivo
     * @return Void
     */
    public function setFile($file, $prefix = "")
    {
        if ($prefix === "") {
            return $this->_fileCache = md5($file) . '.json';
        }

        return $this->_fileCache = $prefix .'_'. md5($file) . '.json';
    }

    /**
     * Obtém uma string formatada com o caminho até o arquivo
     * @access public
     * @return String
     */
    public function getFilePath()
    {
        return sprintf('%s'.DIRECTORY_SEPARATOR.'%s', $this->_folderCache, $this->_fileCache);
    }

    /**
     * Atribui o diretório padrão para cache
     * @access public
     * @param String $folder
     * @return String
    */
    public function setStandardFolder($folder)
    {
        if ($folder != "") {
            return $this->_folderCache = realpath(__DIR__) . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR;
        }

        $this->_folderCache = realpath(__DIR__) . DIRECTORY_SEPARATOR;
    }

    /**
     * Altera o local do arquivo de cache
     * @access public
     * @param String $folder path para o novo local
     * @return Boolean false if fails or true otherwise
     */
    public function alterFolder($folder)
    {
        $newFolder = $this->_folderCache . $folder;

        if (!file_exists($newFolder)) {
            chmod($this->_folderCache, 0755);
            if (!mkdir($newFolder, 0755)) { return false; }
        }

        $this->_folderCache = $newFolder;
        return true;
    }

    /**
     * Tempo para expirar 'altera o padrão'
     * @access public
     * @param Integer $hours
     * @return Void
     */
    public function alterTimeout($hours)
    {
        $this->_timeout = 3600 * (int)$hours;
    }

    /**
     * Verifica se o arquivo já existe
     * @access public
     * @return Boolean false not | true yes
     */
    public function existsFileCache()
    {
        $filecache = $this->getFilePath();

        return file_exists($filecache);
    }

    /**
     * Verifica se o arquivo expirou
     * @access public
     * @return Boolean true - yes | false - not
     */
    public function isTimeout()
    {
        if ($this->existsFileCache() === false) { return true; }

        $filetime = filemtime($this->getFilePath());

        return (time() > ($filetime + $this->_timeout)) ? true : false;
    }

    /**
     * Verifica se o arquivo de cache está pronto para ser usado
     * @access public
     * @return Boolean true sim | false não
    */
    public function isReady()
    {
        if ($this->isTimeout()) { return false; }
        if (!$this->existsFileCache()) { return false; }

        return true;
    }

    /**
     * Atribui os dados ao arquivo de cache, se não existir, cria-o
     * @access public
     * @param Array,String $value dados para cache
     * @return Boolean
     */
    public function writeCache($value)
    {
        $filename = $this->getFilePath();

        return file_put_contents($filename, $value);
    }

    /**
     * Lêr um arquivo de cache
     * @access public
     * @return String or False
     */
    public function readCache()
    {
        $filename = $this->getFilePath();
        //Arquivo não existe
        if (!$this->existsFileCache()) { return false; }
        //Arquivo expirado
        if ($this->isTimeout() === true) { return false; }

        return file_get_contents($filename);
    }

    /**
     * Limpa o cache "Apaga todos os arquivos de cache
     * @access public
     * @return Boolean false se diretório não existe ou não encontrado
    */
    public function clearCache()
    {
        $dir = $this->_folderCache;

        if (!is_dir($dir)) { return false; }

        $resource = opendir($dir);

        while (false !== ($file = readdir($resource))) {
            $dirFile = $dir . DIRECTORY_SEPARATOR . $file;

            if (!is_file($dirFile)) { continue; }

            unlink($dirFile);
        }

        closedir($resource);
    }

}