<?php

namespace Webkul\Varnish\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class FlushVarnishCache extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'varnish:flush {url?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush the varnish cache.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $url = $this->argument('url') ?? '.';

        $this->executeCommand([
            'varnishadm',
            'ban req.url ~ ' . $url,
        ]);

        $this->comment('The varnish cache has been flushed!');
    }

    /**
     * Execute command.
     *
     * @param  array  $command
     * @return \Symfony\Component\Process\Process
     */
    protected function executeCommand(array $command): Process
    {
        $process = new Process($command);

        $process->run();

        if (! $process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process;
    }
}
