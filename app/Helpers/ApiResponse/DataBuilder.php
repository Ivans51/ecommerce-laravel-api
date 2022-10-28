<?php


namespace App\Helpers\ApiResponse;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DataBuilder implements Responsable
{
    /**
     * @var DataBody
     */
    protected DataBody $body;

    /**
     * @var array
     */
    protected array $headers;

    public function __construct()
    {
        $this->fresh();
    }

    /**
     * Reset response body, headers and options
     *
     * @return DataBuilder
     */
    public function fresh(): DataBuilder
    {
        $this->body    = new DataBody();
        $this->headers = [];

        return $this;
    }

    /**
     * Return the set response body
     *
     * @return DataBody
     */
    public function body(): DataBody
    {
        return $this->body;
    }

    /**
     * Set the api data response
     *
     * @param mixed $data
     * @return DataBuilder
     */
    public function data(mixed $data): DataBuilder
    {
        $this->body->setData($data);

        return $this;
    }

    /**
     * Set meta data of current api response
     *
     * @param array $meta
     * @return DataBuilder
     */
    public function meta(array $meta): DataBuilder
    {
        $this->body->setMeta($meta);

        return $this;
    }

    /**
     * Add a response message
     *
     * @param string $messages
     * @return DataBuilder
     */
    public function message(string $messages): DataBuilder
    {
        $this->body->addMessage($messages);

        return $this;
    }

    /**
     * Set collection of response messages
     *
     * @param array $messages
     * @return DataBuilder
     */
    public function messages(array $messages): DataBuilder
    {
        $this->body->setMessages($messages);

        return $this;
    }

    /**
     * Set HTTP status of response
     *
     * @param int $status
     * @return DataBuilder
     */
    public function status(int $status): DataBuilder
    {
        $this->body->setStatus($status);

        return $this;
    }

    /**
     * Set a HTTP header value to be returned with the response
     *
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function header(string $key, string $value): static
    {
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * Set headers that should be returned with the response
     *
     * @param array $headers
     * @return $this
     */
    public function headers(array $headers): static
    {
        $this->headers = array_filter($headers);

        return $this;
    }

    /**
     * Return a json response using the api body
     *
     * @return JsonResponse
     */
    public function respond(): JsonResponse
    {
        return response()->json($this->body->toArray(), $this->body->getStatus(), $this->headers);
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function toResponse($request): JsonResponse
    {
        return $this->respond();
    }

    /**
     * @return DataBuilder
     */
    function api(): DataBuilder
    {
        return new DataBuilder();
    }
}
