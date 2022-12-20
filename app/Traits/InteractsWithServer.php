<?php

namespace App\Traits;

use App\Models\Server;
use Illuminate\Support\Str;
use phpseclib3\Net\SFTP;
use phpseclib3\Net\SSH2;

trait InteractsWithServer
{
    protected Server $server;

    public function connection(): SSH2
    {
        $ssh = new SSH2($this->server->ip,$this->server->port);
        $ssh->login($this->server->username, $this->server->password);
        return $ssh;
    }

    public function connectionWithKey(): SSH2
    {
        $ssh = new SSH2($this->server->ip,$this->server->port);
        ray($ssh);
        return $ssh;
    }

    public function file_connection(): SFTP
    {
        $ssh = new SFTP($this->server->ip,$this->server->port);
        $ssh->login($this->server->username, $this->server->password);
        return $ssh;
    }

    public function sudoCommand(string $command): string
    {
        $sudoWithPassword = fn($password, $command) => 'echo "'.$password.'" | sudo -S '.$command;
        return $sudoWithPassword($this->server->password, $command);
    }

    public function folderExists(string $path): bool
    {
        $command = sprintf('[ -d "%s" ] && echo "exists."', $path);
        $response = $this->connection()->exec($command);
        return Str::of($response ?? '')->contains('exists');
    }

    public function fileExists(string $path): bool
    {
        $command = sprintf('[ -f "%s" ] && echo "exists."', $path);
        $response = $this->connection()->exec($command);
        return Str::of($response ?? '')->contains('exists');
    }

    public function removeFile(string $path): bool
    {
        $command = sprintf('rm -f %s', $path);
        $this->connection()->exec($command);
        return !$this->fileExists($path);
    }

    public function createFolder(string $path): bool
    {
        $command = sprintf('mkdir -p %s', $path);
        $response = $this->connection()->exec($command);
        return Str::of($response ?? '')->contains('exists');
    }
}
