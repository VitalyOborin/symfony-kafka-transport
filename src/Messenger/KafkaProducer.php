<?php

declare(strict_types=1);

namespace VO\KafkaTransport\Messenger;

use RdKafka\Producer as RdKafkaProducer;
use RdKafka\Topic;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Sender\SenderInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use VO\KafkaTransport\Messenger\Stamp\KafkaTopicStamp;

class KafkaProducer implements SenderInterface
{
    private SerializerInterface $serializer;
    private RdKafkaProducer $producer;
    private Topic $topic;

    public function __construct(private readonly KafkaConf $conf)
    {
        $this->serializer = $this->conf->getSerializer();
        $this->producer = new RdKafkaProducer($this->conf->getProducerConf());
        $this->topic = $this->producer->newTopic($this->conf->getTopicName());
    }

    public function send(Envelope $envelope): Envelope
    {
        $envelope = $envelope->with(new KafkaTopicStamp($this->topic->getName()));
        $encodedMessage = $this->serializer->encode($envelope);

        // $this->topic->produce(RD_KAFKA_PARTITION_UA, 0, $encodedMessage['body'], $encodedMessage['key']);
        $this->topic->producev(
            RD_KAFKA_PARTITION_UA,
            0,
            $encodedMessage['body'],
            $encodedMessage['key'] ?? null,
            $encodedMessage['headers'] ?? null,
            $encodedMessage['timestamp_ms'] ?? null
        );
        $this->producer->poll(0);

        return $envelope;
    }

    private function getProducer(): RdKafkaProducer
    {
        return $this->producer ?? $this->producer = new RdKafkaProducer($this->conf->getProducerConf());
    }
}
