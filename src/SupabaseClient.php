<?php

namespace Supabase;

use Supabase\Contracts\SupabaseClientInterface;
use Supabase\Exceptions\SupabaseException;

class SupabaseClient implements SupabaseClientInterface
{
    private string $url;
    private string $secret;
    private array $defaultHeaders;

    public function __construct(string $url, string $secret)
    {
        if (empty($url) || empty($secret)) {
            throw new SupabaseException("URL e API Key do Supabase são obrigatórios.");
        }

        $this->url = $url;
        
        $this->secret = $secret;
        $this->defaultHeaders = [
            "apikey: {$this->secret}",
            "Authorization: Bearer {$this->secret}",
            "Content-Type: application/json",
            "Prefer: return=representation"
        ];
    }

    private function request(string $method, string $endpoint, array $data = [], array $extraHeaders = []): array
    {
        $ch = curl_init();
        $url = $this->url . ltrim($endpoint, '/');

        $headers = array_merge($this->defaultHeaders, $extraHeaders);

        if ($method === 'GET' && !empty($data)) {
            $url .= '?' . http_build_query($data);
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        if ($method !== 'GET' && !empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new SupabaseException("Erro ao conectar à API Supabase: $error", 500);
        }

        $decodedResponse = json_decode($response, true);

        if ($decodedResponse === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new SupabaseException("Erro ao decodificar resposta JSON: " . json_last_error_msg(), $httpCode);
        }

        if ($httpCode >= 400) {
            $errorMessage = $decodedResponse['message'] ?? 'Erro desconhecido na API Supabase';
            throw new SupabaseException("Erro ({$httpCode}): $errorMessage", $httpCode);
        }

        return [
            'status' => $httpCode,
            'response' => $decodedResponse ?? [],
        ];
    }

    public function create(string $table, array $data): array
    {
        if (empty($data)) {
            throw new SupabaseException("Os dados para criação não podem estar vazios.");
        }

        return $this->request('POST', $table, $data);
    }

    public function read(string $table, array $filters = []): array
    {
        return $this->request('GET', $table, $filters);
    }

    public function update(string $table, array $data, array $filters): array
    {
        if (empty($data) || empty($filters)) {
            throw new SupabaseException("Os dados e filtros para atualização são obrigatórios.");
        }

        return $this->request('PATCH', $table . '?' . http_build_query($filters), $data);
    }

    public function delete(string $table, array $filters): array
    {
        if (empty($filters)) {
            throw new SupabaseException("Os filtros para exclusão são obrigatórios.");
        }

        return $this->request('DELETE', $table . '?' . http_build_query($filters));
    }

    public function softDelete(string $table, array $filters): array
    {
        if (empty($filters)) {
            throw new SupabaseException("Os filtros para exclusão lógica são obrigatórios.");
        }

        return $this->request('PATCH', $table . '?' . http_build_query($filters), ['deleted_at' => date('c')]);
    }
}
