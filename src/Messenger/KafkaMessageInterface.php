<?php

declare(strict_types=1);

namespace VO\KafkaTransport\Messenger;

interface KafkaMessageInterface
{
    public function getKey(): string;

    public function getTopic(): string;

    public function getBody(): array;

    public function getOffset(): int;

    public function getTimestamp(): int;
}
