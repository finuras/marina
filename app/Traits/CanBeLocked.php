<?php

namespace App\Traits;

trait CanBeLocked
{
    public function lock(): void
    {
        if($this->isLocked()){
            throw new \Exception('This resource is already locked for provisioning.');
        }

        $this->is_locked = 1;
        $this->save();
    }

    public function lockIfNotLocked(): void
    {
        if(!$this->isLocked()){
            $this->lock();
        }
    }

    public function isLocked(): bool
    {
        return $this->is_locked === 1;
    }

    public function unlock(): void
    {
        $this->is_locked = 0;
        $this->save();
    }
}
