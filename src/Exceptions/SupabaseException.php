<?php

namespace Supabase\Exceptions;

use Exception;

class SupabaseException extends Exception
{
    private int $httpCode;
    private ?array $apiResponse;

    public function __construct(string $message, int $httpCode = 0, ?array $apiResponse = null)
    {
        parent::__construct($message, $httpCode);
        $this->httpCode = $httpCode;
        $this->apiResponse = $apiResponse;
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }
    
    public function getApiResponse(): ?array
    {
        return $this->apiResponse;
    }
}
