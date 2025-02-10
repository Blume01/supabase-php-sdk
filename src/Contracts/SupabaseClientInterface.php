<?php

namespace Supabase\Contracts;

interface SupabaseClientInterface
{
    public function create(string $table, array $data): array;
    public function read(string $table, array $filters = []): array;
    public function update(string $table, array $data, array $filters): array;
    public function delete(string $table, array $filters): array;
}
