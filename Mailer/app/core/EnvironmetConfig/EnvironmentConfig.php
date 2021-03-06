<?php

namespace Remp\MailerModule;

class EnvironmentConfig
{
    private $linkedServices = [];

    public function linkService($code, $url, $icon)
    {
        if (empty($url)) {
            return;
        }
        $this->linkedServices[$code] = [
            'url' => $url,
            'icon' => $icon,
        ];
    }

    public function getLinkedServices()
    {
        return $this->linkedServices;
    }

    public function get($key)
    {
        return getenv($key);
    }

    public function getDsn()
    {
        $port = $this->get('DB_PORT');
        if (!$port) {
            $port = 3306;
        }

        return $this->get('DB_ADAPTER') .
            ':host=' . $this->get('DB_HOST') .
            ';dbname=' . $this->get('DB_NAME') .
            ';port=' . $port;
    }
}
