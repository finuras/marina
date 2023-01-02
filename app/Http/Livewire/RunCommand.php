<?php

namespace App\Http\Livewire;

use Livewire\Component;

class RunCommand extends Component
{
    public $activity;

    public $isKeepAliveOn = false;

    public $manualKeepAlive = false;

    public $command = '';

    public $cwd = null;

    public function render()
    {
        return view('livewire.run-command');
    }

    public function mount($cwd)
    {
        $this->cwd = $cwd;
    }

    public function runCommand()
    {
        ray()->clearAll();

        $this->activity = activity()
            ->withProperty('status', 'running')
            ->log("Running command...\n\n");

        $this->isKeepAliveOn = true;

        ray($this->cwd);

        dispatch(new \App\Jobs\ExecuteProcess($this->activity, $this->command, $this->cwd));
    }

    public function polling()
    {
        $this->activity?->refresh();

        if ($this->activity?->properties['status'] === 'finished') {
            $this->isKeepAliveOn = false;
        }
    }
}
