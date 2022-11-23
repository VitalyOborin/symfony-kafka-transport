<?php

declare(strict_types=1);

namespace VO\KafkaTransport\Messenger;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\TransportInterface;

class KafkaTransport implements TransportInterface
{
    private readonly KafkaConsumer $consumer;
    private readonly KafkaProducer $producer;
    private readonly KafkaConf $conf;

    public function __construct(KafkaConf $conf)
    {
        $this->conf = $conf;
    }

    /**
     * {@inheritDoc}
     */
    public function get(): iterable
    {
        return $this->getConsumer()->get();
    }

    /**
     * {@inheritDoc}
     */
    public function ack(Envelope $envelope): void
    {
        $this->getConsumer()->ack($envelope);
    }

    /**
     * {@inheritDoc}
     */
    public function reject(Envelope $envelope): void
    {
        $this->getConsumer()->reject($envelope);
    }

    /**
     * {@inheritDoc}
     */
    public function send(Envelope $envelope): Envelope
    {
        return $this->getProducer()->send($envelope);
    }

    private function getConsumer(): KafkaConsumer
    {
        return $this->consumer ?? $this->consumer = new KafkaConsumer($this->conf);
    }

    private function getProducer(): KafkaProducer
    {
        return $this->producer ?? $this->producer = new KafkaProducer($this->conf);
    }
}
