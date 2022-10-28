<?php


namespace App\Helpers\ApiResponse;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\JsonEncodingException;

class DataBody implements Arrayable, Jsonable
{

    /**
     * Collect of json data to return
     *
     * @var array
     */
    private mixed $data;

    /**
     * Meta values of response
     *
     * @var array
     */
    private array $meta;

    /**
     * Collection of messages
     *
     * @var array
     */
    private array $messages;

    /**
     * Status code of the response
     *
     * @var int $status
     */
    private int $status;

    public function __construct($data = [], $meta = [], $messages = [], int $status = 200)
    {
        $this->setData($data);
        $this->setMeta($meta);
        $this->setMessages($messages);
        $this->setStatus($status);
    }

    /**
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     *
     * @return DataBody
     */
    public function setData(mixed $data): DataBody
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @param mixed $messages
     *
     * @return DataBody
     */
    public function setMessages(array $messages): DataBody
    {
        $this->messages = $messages;

        return $this;
    }

    /**
     * @param string $message
     *
     * @return DataBody
     */
    public function addMessage(string $message): DataBody
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMeta(): array
    {
        return $this->meta;
    }

    /**
     * @param mixed $meta
     *
     * @return DataBody
     */
    public function setMeta(mixed $meta): DataBody
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return DataBody
     */
    public function setStatus(int $status): DataBody
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0): string
    {
        $json = json_encode($this->toArray(), $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new JsonEncodingException;
        }

        return $json;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'status' => $this->getStatus(),
            'body' => $this->getData(),
            'meta' => $this->getMeta(),
            'messages' => $this->getMessages(),
        ];
    }
}
