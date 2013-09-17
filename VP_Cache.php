<?php

/**
 * Classe de cache para PHP, facil implementacao
 * API Documentation: https://github.com/valmirphp/VP_Cache
 * 
 * @author Valmir Barbosa dos Santos <valmir.php@gmail.com>
 * @version 1
 */
class VpCache {

    /**
     * Armazena Cache durante execução
     *
     * @var string
     */
    protected static $dataCache = null;

    /**
     * Responsaval por retornar o diretorio do cache e nome do arquivo
     * Mude a gosto :)
     * 
     * @return string
     */
    protected static function getCacheDir() {
        //$path = WP_CONTENT_DIR . '/uploads/cache/'; //dica de path para Wordpress
        $path = __DIR__;
        $filename = $path . '/' . sha1('VpCache') . '.meta';
        return $filename;
    }

    /**
     * Retorna um array de objetos armazenados no cache
     * este methodo é baseada em singleton, como a classe é estatica ela carrega apenas uma vez,
     * O arquivo de cache é carregado apenas uma vez para memoria,
     * 
     * @return array(objetc)
     */
    private static function getDataCache() {
        if (is_null(self::$dataCache)) {
            self::$dataCache = self::_loadCache();
        }
        return self::$dataCache;
    }

    /**
     * Esta funcao é a cereja da classe,
     * Foi baseada no cache do laravel, na qual ela na mesma funcao temos o get e o save.
     * Ou seja ele verifica se a key esta em cache, se tiver ele retorna o cache claso contrario 
     * ele executa a funcao passada em parametro e armazena no cache
     * 
     * @param string $key
     * @param integer $minutes
     * @param function anonima $function
     * @return array $data
     */
    public static function remember($key, $expire, $function) {
        $cachedData = self::getDataCache();
        $data = (isset($cachedData[$key])) ? $cachedData[$key] : null;

        if (self::checkDataExpired($data) === false && isset($data['data'])) {
            return $data['data'];
        }

        $value = $function();
        
        if (is_string($value))
            $value = self::removeEspaco($value);

        self::save($key, $value, $expire);
        return $value;
    }

    /**
     * Cacheia um include de um arquivo php
     *
     * @param string $path nome do arquivo php 
     * @param integer $minutes
     * @return array $data
     */
    public static function rememberInclude($path, $expire) {
        
        $key = md5($path);
        if ($expire == 0) {
            return self::RenderPath($path);
        }
        $cachedData = self::getDataCache();
        $data = (isset($cachedData[$key])) ? $cachedData[$key] : null;

        if (self::checkDataExpired($data) === false && isset($data['data'])) {
            return $data['data'];
        }

        $value = self::RenderPath($path);
        self::save($key, $value, $expire);

        return $value;
    }

    /**
     * Retorna valor armazenado no cache
     * 
     * @param string $key
     * @return $value 
     */
    public static function get($key) {
        $cachedData = self::getDataCache();
        $data = (isset($cachedData[$key])) ? $cachedData[$key] : null;

        if (self::checkDataExpired($data)) {
            self::delete($key);
            return null;
        }

        $value = (isset($data['data'])) ? $data['data'] : null;
        return $value;
    }

    /**
     * Salva dados no cache, podendo passar um tempo baseado em minutos para expiracao do valor
     * 
     * @param string $key nome da cache
     * @param $value o valor que sera salvo no cahce
     * @param integer $expire minutos de duração do cache
     * @return void
     */
    public static function save($key, $value, $expire = 0) {

        $cachedData = self::getDataCache();
        $expire = ($expire) ? (time() + ($expire * 60)) : 0;
        $storeData = array(
            'time' => time(),
            'expire' => $expire,
            'data' => $value
        );
        $cachedData[$key] = $storeData;
        self::$dataCache = $cachedData;
        self::StoreData();
    }

    /**
     * Apaga esta key do cache
     * 
     * @param string $key
     * @return void
     */
    public static function delete($key) {
        self::getDataCache();
        if (isset(self::$dataCache[$key])) {
            unset(self::$dataCache[$key]);
            self::StoreData();
        }
    }

    /**
     * Apaga todo o cache
     * 
     * @return void
     */
    public static function deleteAll() {
        self::$dataCache = null;
        self::StoreData();
    }

    /**
     * Apaga apenas os caches expirados
     * 
     * @return void
     */
    public static function deleteExpired() {
        $cachedData = self::getDataCache();
        foreach ($cachedData as $key => $data) {
            if (self::checkDataExpired($data)) {
                unset(self::$dataCache[$key]);
            }
        }

        self::StoreData();
    }

    /**
     * Carrega os valores cacheados para a classe
     * 
     * @return mixed
     */
    protected static function _loadCache() {
        $fileName = self::getCacheDir();
        if (true === file_exists($fileName)) {
            $file = file_get_contents($fileName);
            return json_decode($file, true);
        } else {
            return array();
        }
    }

    /**
     * Salva o arquivo no disco
     * 
     * @return void
     */
    protected static function StoreData() {
        $cacheData = json_encode(self::$dataCache);
        $r = file_put_contents(self::getCacheDir(), $cacheData);

        if (!$r) {
            die("<b>Falha ao savar arquivo do cache:</b>" . self::getCacheDir() );
        }
    }

    /**
     * verifica se data expirou
     * retorna true caso caso data expirada
     * 
     * @return boean
     */
    protected static function checkDataExpired($data) {

        if (isset($data['expire']) === true && $data['expire'] > 0 && $data['expire'] < time()) {
            return true;
        }

        return false;
    }

    /**
     * Remove todos os espaços da string
     * 
     * @param type $str
     * @return string 
     */
    protected static function removeEspaco($str) {
        /// First remove the leading/trailing whitespace
        $str = trim($str);
        // Now remove any doubled-up whitespace
        $str = preg_replace('/\s(?=\s)/', '', $str);
        // Finally, replace any non-space whitespace, with a space
        $str = preg_replace('/[\n\r\t]/', ' ', $str);

        return $str;
    }

    /**
     * Capitura os dados do arquivo
     * 
     * @param strin $path nome do arquivo php
     * @return string
     */
    protected static function RenderPath($path) {

        if (file_exists($path) == false) {
            die("Arquivo não encontrado: $path");
        }

        ob_start();
        require $path;
        $ob = ob_get_clean();

        return self::removeEspaco($ob);
    }

}
