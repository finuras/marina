<?php

namespace App\Actions;

use App\Models\Server;
use App\Traits\InteractsWithServer;
use Exception;
use phpseclib3\Net\SSH2;
use Spatie\Ssh\Ssh;

class ServerInstallDocker
{
    use InteractsWithServer;

    protected $connection;
    protected $ssh_server_fp;

    public function execute(Server $server): bool
    {
        $this->server = $server;

        $server->lock();

        $process = Ssh::create($server->username, $server->ip)
            ->usePrivateKey('/mnt/.ssh/id_rsa')
            ->disableStrictHostKeyChecking()
            ->execute([
                // Set up the repository
                'apt-get update',
                'apt-get install -y ca-certificates curl gnupg lsb-release',
                'mkdir -p /etc/apt/keyrings',
                'curl -fsSL https://download.docker.com/linux/ubuntu/gpg | gpg --batch --yes --dearmor -o /etc/apt/keyrings/docker.gpg',
                'echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | tee /etc/apt/sources.list.d/docker.list > /dev/null',
                // Install Docker Engine
                'apt-get update',
                'apt-get -y install docker-ce docker-ce-cli containerd.io docker-compose-plugin',
                'docker info',
            ]);

        if(! $process->isSuccessful()) {

            ray($process->getErrorOutput());

            activity()
                ->performedOn($server)
                ->withProperty('status', 'error')
                ->withProperty('output', $process->getErrorOutput())
                ->log($process->getOutput());
        }

        ray($process->getOutput());

        activity()
            ->performedOn($server)
            ->withProperty('status', 'success')
            ->withProperty('output', $process->getOutput())
            ->log($process->getOutput());

        $server->unlock();

        return true;
    }
}
