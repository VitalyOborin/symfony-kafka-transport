<?php

declare(strict_types=1);

namespace VO\KafkaTransport\Messenger\Stamp;

use RdKafka\Message;
use Symfony\Component\Messenger\Stamp\NonSendableStampInterface;

final class KafkaMessageStamp implements NonSendableStampInterface
{
    private Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }
}
