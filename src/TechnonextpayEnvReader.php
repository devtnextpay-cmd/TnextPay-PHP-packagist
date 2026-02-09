<?php

namespace TechnonextPlugin;

use TechnonextPlugin\TechnonextpayConfig;

require_once __DIR__ . '/TechnonextpayConfig.php';

/**
 * 
 * Thanks to F R Michel.
 * Note: https://dev.to/fadymr/php-create-your-own-php-dotenv-3k2i
 * @since 2023-01-17 
 */
class TechnonextpayEnvReader
{

    /** directory where the .env file can be located */
    protected $path;

    public function __construct(string $path)
    {
        if (!file_exists($path)) {
            throw new \InvalidArgumentException(sprintf('%s does not exist', $path));
        }
        $this->path = $path;
    }

    public function load(): void
    {
        if (!is_readable($this->path)) {
            throw new \RuntimeException(sprintf('%s file is not readable', $this->path));
        }

        $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {

            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }

    public function getConfig()
    {
        $this->load();
        $config = new TechnonextpayConfig();
        $config->merchant_code = getenv('MERCHANT_CODE');
        $config->api_key = getenv('API_KEY');
        $config->api_secret = getenv('API_SECRET');
        $config->api_endpoint = getenv('TechnonextPay_API_ENDPOINT');      
        $config->success_url = getenv('SUCCESS_URL');
        $config->failure_url = getenv('FAILURE_URL');       
        $config->cancel_url = getenv('CANCEL_URL');
        $config->ipn_url = getenv('IPN_URL');
        $config->log_path = getenv('LOG_LOCATION');

        $config->ssl_verifypeer = getenv('CURLOPT_SSL_VERIFYPEER');

        return $config;
    }
}
