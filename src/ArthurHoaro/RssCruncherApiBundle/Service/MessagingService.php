<?php
/**
 * QueueManager.php
 * Author: arthur
 */

namespace ArthurHoaro\RssCruncherApiBundle\Service;


use SimplePMS\SimplePMS;

class MessagingService
{
    protected $manager;

    public function __construct($dsn, $host, $port, $dbname, $user, $password)
    {
        $connectionString = $dsn . 'host=' . $host . ';';
        $connectionString .= !empty($port) ? 'port=' . $port . ';' : '';
        $connectionString .= 'dbname=' . $dbname;

        $pdo = new \PDO($connectionString, $user, $password);
        $this->manager = new SimplePMS();
        $this->manager->setPdo($pdo);
    }

    public function getManager()
    {
        return $this->manager;
    }
}