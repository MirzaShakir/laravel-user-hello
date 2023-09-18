<?php

namespace App\Helpers;

use Illuminate\Http\Response;

trait JsonResponse {
    /**
     * @param array $data
     * @param $status_code
     * @return Response
     */
    public function setSuccess(string $message, array $data = [], $status_code = 200 ): Response
    {
        return $this->getJsonResponse($data, $message, $status_code );
    }

    /**
     * @param string $message
     * @param array $data
     * @param int $status_code
     * @return Response
     */
    public function setError(string $message, array $data = [], int $status_code = 417 ): Response
    {
        return $this->getJsonResponse($data, $message, $status_code, false );

    }

    public function setJsonResponse($data, $status_code ): Response
    {
        return response( $data, $status_code, [
            'Content-Type', 'application/json'
        ]);
    }

    /**
     * @param array $data
     * @param string $message
     * @param mixed $status_code
     * @return Response
     */
    public function getJsonResponse(array $data, string $message, mixed $status_code, bool  $hasNoError = true ): Response
    {
        return $this->setJsonResponse(
            array_merge($data, ['message' => $message, 'success' => $hasNoError ]), $status_code
        );
    }
}
