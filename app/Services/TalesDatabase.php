<?php
namespace App\Services;

use Illuminate\Database\Connection;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Throwable;

class TalesDatabase
{
    private bool $sharedTransaction = false;

    public function sharedCapable(): bool
    {
        if (!config('talesrunner.shared_transactions')) return false;
        $g = config('database.connections.game');
        $w = config('database.connections.web');
        foreach (['host','port','username','password','charset'] as $key) {
            if (($g[$key] ?? null) !== ($w[$key] ?? null)) return false;
        }
        return true;
    }

    public function game(): Connection { return DB::connection('game'); }
    public function web(): Connection { return DB::connection('web'); }
    public function gameTable(string $table): Builder { return $this->game()->table($table); }

    public function webTable(string $table): Builder
    {
        if ($this->sharedTransaction) {
            $schema = (string) config('database.connections.web.database');
            return $this->game()->table($schema.'.'.$table);
        }
        return $this->web()->table($table);
    }

    public function transaction(callable $callback): mixed
    {
        if ($this->sharedCapable()) {
            return $this->game()->transaction(function () use ($callback) {
                $this->sharedTransaction = true;
                try { return $callback($this); }
                finally { $this->sharedTransaction = false; }
            }, 3);
        }

        $game = $this->game();
        $web = $this->web();
        $game->beginTransaction();
        $web->beginTransaction();
        try {
            $result = $callback($this);
            $game->commit();
            $web->commit();
            return $result;
        } catch (Throwable $e) {
            if ($game->transactionLevel() > 0) $game->rollBack();
            if ($web->transactionLevel() > 0) $web->rollBack();
            throw $e;
        }
    }
}
