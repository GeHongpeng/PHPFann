<?php
namespace OpensslOperation;

/**
 * Class OpensslClient
 * @package OpensslOperation
 */
class OpensslClient{
    /**
     * @var
     */
    protected $key;
    /**
     * @var string
     */
    protected $method;
    /**
     * @var
     */
    protected $iv_a;
    /**
     * @var
     */
    protected $iv;
    /**
     * @var int
     */
    protected $opt;

    /**
     * OpensslClient constructor.
     */
    public function __construct() {
        // 暗号化メソッドを指定
        $this->method = 'aes-256-cbc';

        // 初期化ベクトルを設定
        $iv_a = [223,156,39,243,44,53,136,185,62,154,223,69,84,246,181,219,
                 98,18,130,90,150,222,24,220,46,134,135,151,18,104,103,117];
        $this->setIv($iv_a);

        // オプションを設定
        $this->opt = 0;
    }

    /**
     * @param $data
     * @return string
     */
    public function encrypt($data) {
        return openssl_encrypt($data, $this->method, $this->key, $this->opt, $this->iv);
    }

    /**
     * @param $enc
     * @return string
     */
    public function decrypt($enc) {
        return openssl_decrypt($enc, $this->method, $this->key, $this->opt, $this->iv);
    }

    /**
     * @param $method
     */
    public function setMethod($method) {
        $this->method = $method;
    }

    /**
     * @param $iv_a
     */
    public function setIv($iv_a) {
        //
        $this->iv_a = $iv_a;
        $this->iv = implode("", array_map("chr", $this->iv_a));
        // 初期化ベクトルの長さを調べてサイズを調節
        $ivLen = openssl_cipher_iv_length($this->method);
        $this->iv = substr($this->iv, 0, $ivLen);
    }

    /**
     * @param $opt
     */
    public function setOpt($opt) {
        $this->opt = $opt;
    }
}