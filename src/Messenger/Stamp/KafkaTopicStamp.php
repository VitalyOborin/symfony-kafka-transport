<?php

declare(strict_types=1);

namespace VO\KafkaTransport\Messenger\Stamp;

use Symfony\Component\Messenger\Stamp\StampInterface;

class KafkaTopicStamp implements StampInterface
{
    public function __construct(private readonly string $topic)
    {
    }

    public function getTopicName(): string
    {
        return $this->topic;
    }
}
