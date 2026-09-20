<?php
namespace App\Services;

class ItemCatalogService
{
    private ?array $map = null;
    private function load(): array
    {
        if ($this->map !== null) return $this->map;
        $json = @file_get_contents(public_path('items.json')) ?: '{}';
        $decoded = json_decode($json, true);
        $this->map = [];
        foreach (($decoded['result'] ?? []) as $row) {
            if (isset($row['fdItemNum'])) $this->map[(int)$row['fdItemNum']] = $row;
        }
        return $this->map;
    }
    public function name(int $id, ?string $fallback=null): string { return (string)($this->load()[$id]['fdItemName'] ?? $fallback ?? "Item #{$id}"); }
    public function description(int $id): string { return (string)($this->load()[$id]['fdDesc'] ?? 'No description available.'); }
    public function image(int $id): string
    {
        foreach (["itemimage/{$id}.png","images/items/{$id}.png"] as $candidate) if (is_file(public_path($candidate))) return asset($candidate);
        return asset('images/image.png');
    }
}
