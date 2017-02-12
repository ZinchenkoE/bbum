<?php
namespace app\components\interfaces;

interface ModelInterface
{
    public static function initModel();
    public function run($key, $id);
}
