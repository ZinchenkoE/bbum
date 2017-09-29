<?php
namespace app\components;
use Yii;

final class Logger
{
    const FILE_NAME = "log.txt";

    private static function writeData(string $data)
    {
        file_put_contents(
            (Yii::$app->basePath . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . self::FILE_NAME),
            self::curTime() . $data . "\n" . self::conditions() . self::createDelimiter(),
            FILE_APPEND | LOCK_EX
        );
    }

    public static function logJs($var)
    {
        try {
            self::writeData($var);
        } catch (\Throwable $t) { }
    }

    public static function logException($e, string $comment = '')
    {
        if ($e instanceof \Throwable) {
            self::logError($e->getFile() . ":" . $e->getLine(), $e->getMessage(), $comment);
        }
        return $e;
    }

    public static function logError($file, $message, $comment = null)
    {
        try {
            self::writeData(
                "\tCaught in {$file}. \n\tError: \"{$message}\"" . ($comment ? "\n\tComment: {$comment}" : "")
            );
        } catch (\Throwable $t) { }
    }

    public static function log($message, $comment = null)
    {
        try {
            self::writeData("\tMessage: \"{$message}\"" . ($comment ? "\n\tComment: {$comment}" : ""));
        } catch (\Throwable $t) { }
    }

    private static function createDelimiter(string $char = '-', int $loop = 7)
    {
        $delimiter = $char ? substr($char, 0, 1) : '-';
        for ($i = 0; $i < $loop; $i++){
            $delimiter .= $delimiter;
        }
        return "\n{$delimiter}\n";
    }

    private static function curTime()
    {
        $currTime  = date('H:i:s d F Y', time());
        return $currTime . "\n";
    }

    private static function conditions()
    {
        return "\nIP: {$_SERVER['REMOTE_ADDR']}\nRequest: \"{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}\"\nHTTP user agent: {$_SERVER['HTTP_USER_AGENT']}\nUSER: " .
         ( Yii::$app->user->isGuest ? '<GUEST>' : (Yii::$app->user->identity->login . ", ID: " . Yii::$app->user->identity->getId()));
    }
}