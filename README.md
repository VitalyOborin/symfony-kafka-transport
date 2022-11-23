# Kafka Transport for Symfony Messenger

Дополнительный транспорт для [symfony/messenger](https://symfony.com/doc/current/messenger.html), позволяющий использовать транспорт [Apache Kafka](https://kafka.apache.org/). Реализовано как бандл для symfony.

## Установка

```console
$ composer req vitalyoborin/symfony-kafka-transport
```

## Настройка
В файле .env или другим способом создать переменную окружения, которая будет использоваться для хранения адресов брокеров, например MESSENGER_TRANSPORT_DSN_KAFKA. Адреса брокеров должны начинаться с префикса `rdkafka://`. Если брокеров несколько, то каждый брокер указывается в той же строке и тоже с префиксом.

Пример одного брокера для файла .env: `MESSENGER_TRANSPORT_DSN_KAFKA=rdkafka://kafka:9092`

Пример нескольких брокеров для файла .env: `MESSENGER_TRANSPORT_DSN_KAFKA=rdkafka://kafka:9092,rdkafka://kafka:9093,rdkafka://kafka:9094`

В файле конфигурации для messenger (обычно `config/packages/messenger.yaml`) создать новый транспорт
```yaml
framework:
    messenger:
        # ...
        buses:
            kafka.bus: ~

        transports:
            # ...
            kafka.products:
                dsn: '%env(resolve:MESSENGER_TRANSPORT_DSN_KAFKA)%'
                options:
                    topic: 'products'
                    group_id: 'consumer-products-messenger'
                    receiveTimeout: 60000
```
Если существует несколько шин или для каждого топика в Kafka создан отдельный транспорт, то можно указать роутинг для сообщений по классу или интерфейсу .

В файле `config/services.yaml` установить теги для хэндлеров сообщений данной шины
```yaml
services:
    _instanceof:
        App\Handler\KafkaHandlerInterface:
            tags: [{ name: messenger.message_handler, bus: kafka.bus}]
```
В данном примере хэндлеры реализуют интерфейс `App\Handler\KafkaHandlerInterface` - это может быть пустой интерфейс. Также хэндлером может быть класс с конкретной реализацией обработки сообщений, точно также помеченный тегом `messenger.message_handler`. Параметр bus в теге необходимо указывать, если шин несколько и для каждой шины необходим свой хэндлер.

В общем случае настройка транспорта и шин обычная для symfony/messenger. Транспорт подцепляется исключительно по наличию `rdkafka://` в DSN.

## Расширенная настройка
В файле конфигурации messenger.yaml для конкретного транспорт необходимо определить значения options. Обязательными являются topic и group_id, название топика и группы консьюмеров соответственно. Остальные параметры соответствуют парамтерам настройки Kafka

## Todo
* Реализация настроект Kafka для топиков и для подключения через options
* Работа с header
* Работа с SSL, авторизация в Kafka

## Другие реализации
[KonstantinCodes/messenger-kafka](https://github.com/KonstantinCodes/messenger-kafka)