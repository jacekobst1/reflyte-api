<?php

declare(strict_types=1);

namespace Tests\Unit\Jobs\IntegrateWithEsp;

use App\Jobs\IntegrateWithEsp\IntegrateWithEspService;
use App\Jobs\SynchronizeSubscriber\SynchronizeSubscriberJob;
use App\Modules\Esp\Dto\EspSubscriberDto;
use App\Modules\Esp\EspName;
use App\Modules\Esp\Integration\EspClientFactory;
use App\Modules\Esp\Integration\MailerLite\Dto\ResponseLinksDto;
use App\Modules\Esp\Integration\MailerLite\MailerLiteEspClient;
use App\Modules\Newsletter\Vo\NewsletterEspConfig;
use Illuminate\Bus\PendingBatch;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Str;
use Tests\TestCase;
use Throwable;

final class IntegrateWithEspServiceTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function testHandle(): void
    {
        Bus::fake([SynchronizeSubscriberJob::class]);

        // given
        $espConfig = new NewsletterEspConfig(
            newsletterId: Str::uuid(),
            espName: EspName::MailerLite,
            espApiKey: 'test',
        );
        $espSubscribers = EspSubscriberDto::collection([
            [
                'id' => 'test',
                'email' => 'test@test.com',
                'status' => 'active',
            ],
            [
                'id' => 'test2',
                'email' => 'test2@test.com',
                'status' => 'inactive',
            ]
        ]);
        $responseLinksDto = new ResponseLinksDto(null, null, null, null);

        // mock
        $mailerLiteEspClientMock = $this->mock(MailerLiteEspClient::class);
        $mailerLiteEspClientMock->shouldReceive('getSubscribersBatch')
            ->once()
            ->andReturn([$espSubscribers, $responseLinksDto]);
        $mailerLiteEspClientMock->shouldReceive('getSubscribersTotalNumber')
            ->once()
            ->andReturn(2);
        $espClientFactoryMock = $this->mock(EspClientFactory::class);
        $espClientFactoryMock->shouldReceive('make')
            ->once()
            ->andReturn($mailerLiteEspClientMock);

        // when
        /** @var IntegrateWithEspService $service */
        $service = App::make(IntegrateWithEspService::class);
        $service->handle($espConfig);

        // then
        Bus::assertBatched(function (PendingBatch $batch) use ($espConfig) {
            return $batch->name === "Synchronize subscribers | newsletterId: $espConfig->newsletterId"
                && $batch->jobs->count() === 2;
        });
    }
}
