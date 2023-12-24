<?php

declare(strict_types=1);

namespace Tests\Unit\Jobs\SynchronizeSubscriber;

use App\Jobs\SynchronizeSubscriber\SynchronizeSubscriberService;
use App\Modules\Esp\Dto\EspSubscriberDto;
use App\Modules\Esp\EspName;
use App\Modules\Esp\Services\EspSubscriberUpdater;
use App\Modules\Newsletter\Vo\NewsletterEspConfig;
use App\Modules\Subscriber\Services\Internal\SubscriberFromEspCreator;
use App\Modules\Subscriber\Subscriber;
use App\Modules\Subscriber\SubscriberStatus;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Tests\TestCase;

final class SynchronizeSubscriberServiceTest extends TestCase
{
    public function testHandle(): void
    {
        // given
        $espConfig = new NewsletterEspConfig(
            newsletterId: Str::uuid(),
            espName: EspName::MailerLite,
            espApiKey: 'test',
        );
        $subscriberDto = new EspSubscriberDto('identifier', 'email@test.com', SubscriberStatus::Active);
        $subscriber = Subscriber::factory()->create();

        // mock
        $this->mock(SubscriberFromEspCreator::class)
            ->shouldReceive('firstOrCreate')
            ->once()
            ->with($espConfig->newsletterId, $subscriberDto)
            ->andReturn($subscriber);
        $this->mock(EspSubscriberUpdater::class)
            ->shouldReceive('fillFields')
            ->once()
            ->with($espConfig, $subscriberDto->id, $subscriber);

        // when
        /** @var SynchronizeSubscriberService $service */
        $service = App::make(SynchronizeSubscriberService::class);
        $service->handle($espConfig, $subscriberDto);
    }
}
