<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusCoinbasePlugin\Behat\Mocker;

use BitBag\SyliusCoinbasePlugin\ApiClient\CoinbaseApiClientInterface;
use CoinbaseCommerce\Resources\Charge;
use CoinbaseCommerce\Resources\Event;
use Sylius\Behat\Service\Mocker\MockerInterface;

final class CoinbaseApiMocker
{
    /** @var MockerInterface */
    private $mocker;

    public function __construct(MockerInterface $mocker)
    {
        $this->mocker = $mocker;
    }

    public function mockApiCreatePayment(callable $action): void
    {
        $mock = $this->mocker->mockService('bitbag_sylius_coinbase_plugin.api_client.coinbase', CoinbaseApiClientInterface::class);

        $mock
            ->shouldReceive('initialise')
        ;

        $charge = \Mockery::mock('charge', Charge::class);

        $charge->id = 'test';
        $charge->hosted_url = 'test';
        $charge->timeline = [
            [
                'status' => CoinbaseApiClientInterface::STATUS_NEW,
            ],
        ];

        $mock
            ->shouldReceive('create', 'show')
            ->andReturn($charge)
        ;

        $action();

        $this->mocker->unmockAll();
    }

    public function mockApiSuccessfulPayment(callable $action): void
    {
        $mock = $this->mocker->mockService('bitbag_sylius_coinbase_plugin.api_client.coinbase', CoinbaseApiClientInterface::class);

        $mock
            ->shouldReceive('initialise')
        ;

        $charge = \Mockery::mock('charge', Charge::class);

        $charge->id = 'test';
        $charge->hosted_url = 'test';
        $charge->timeline = [
            [
                'status' => CoinbaseApiClientInterface::STATUS_NEW,
            ],
            [
                'status' => CoinbaseApiClientInterface::STATUS_COMPLETED,
            ]
        ];

        $mock
            ->shouldReceive('create', 'show')
            ->andReturn($charge)
        ;

        $event = \Mockery::mock('event', Event::class);

        $event->type = 'charge:confirmed';

        $mock
            ->shouldReceive('buildEvent')
            ->andReturn($event)
        ;

        $action();

        $this->mocker->unmockAll();
    }

    public function mockApiFailedPayment(callable $action): void
    {
        $mock = $this->mocker->mockService('bitbag_sylius_coinbase_plugin.api_client.coinbase', CoinbaseApiClientInterface::class);

        $mock
            ->shouldReceive('initialise')
        ;

        $charge = \Mockery::mock('charge', Charge::class);

        $charge->id = 'test';
        $charge->hosted_url = 'test';
        $charge->timeline = [
            [
                'status' => CoinbaseApiClientInterface::STATUS_NEW,
            ],
            [
                'status' => CoinbaseApiClientInterface::STATUS_FAILED,
            ]
        ];

        $mock
            ->shouldReceive('create', 'show')
            ->andReturn($charge)
        ;

        $event = \Mockery::mock('event', Event::class);

        $event->type = 'charge:confirmed';

        $mock
            ->shouldReceive('buildEvent')
            ->andReturn($event)
        ;

        $action();

        $this->mocker->unmockAll();
    }

    public function mockApiCancelledPayment(callable $action): void
    {
        $mock = $this->mocker->mockService('bitbag_sylius_coinbase_plugin.api_client.coinbase', CoinbaseApiClientInterface::class);

        $mock
            ->shouldReceive('initialise')
        ;

        $charge = \Mockery::mock('charge', Charge::class);

        $charge->id = 'test';
        $charge->hosted_url = 'test';
        $charge->timeline = [
            [
                'status' => CoinbaseApiClientInterface::STATUS_NEW,
            ],
            [
                'status' => CoinbaseApiClientInterface::STATUS_CANCELED,
            ]
        ];

        $mock
            ->shouldReceive('create', 'show')
            ->andReturn($charge)
        ;

        $event = \Mockery::mock('event', Event::class);

        $event->type = 'charge:failed';

        $mock
            ->shouldReceive('buildEvent')
            ->andReturn($event)
        ;

        $action();

        $this->mocker->unmockAll();
    }
}
