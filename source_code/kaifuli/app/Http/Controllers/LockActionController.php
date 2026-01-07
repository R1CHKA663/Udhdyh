<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LockActionController
{
    static $login;
    static $lockFile;
    static $locksDirectory;
    static $lockFilePath;

    public function __construct(string $login, string $action)
    {
        self::login = \Auth::user()->id;
        self::locksDirectory = '../Middleware/DelayUser.php';
        if (!file_exists(self::locksDirectory)) {
            mkdir(self::locksDirectory, 0755, true);
        }
        self::lockFilePath = self::locksDirectory . self::login;
    }

    static function lock()
    {
        $this->lockFile = fopen($this->lockFilePath, 'w+');
        flock($this->lockFile, LOCK_EX | LOCK_NB);
    }

    static function unlock()
    {
        if (!file_exists(self::lockFilePath)) {
            return false;
        }

        flock(self::lockFile, LOCK_UN);
        fclose(self::lockFile);
        unlink(self::lockFilePath);
    }

    static function isLocked(): bool
    {
        if (!file_exists(self::lockFilePath)) {
            return false;
        }

        $this->lockFile = fopen($this->lockFilePath, 'w+');
        return !flock($this->lockFile, LOCK_EX | LOCK_NB);
    }
}
