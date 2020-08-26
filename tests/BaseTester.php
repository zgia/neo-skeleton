<?php

use Neo\Neo;
use PHPUnit\Framework\TestCase;

/**
 * 测试基类
 *
 * Class BaseTester
 *
 * @internal
 * @coversNothing
 */
class BaseTester extends TestCase
{
    /**
     * @var Neo $neo
     */
    protected $neo;


    protected function setUp(): void
    {
        $this->neo = neo();
    }

    /**
     * @param string $msg
     */
    public function outlog($msg)
    {
        $time = formatDate('Y-m-d H:i:s', time());
        echo "{$time} {$msg}" . PHP_EOL;
    }
}
