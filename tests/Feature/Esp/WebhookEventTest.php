<?php

declare(strict_types=1);

namespace Tests\Feature\Esp;

use App\Modules\Esp\Integration\Clients\ConvertKit\ConvertKitClient;
use App\Modules\Esp\Integration\Clients\EspClientFactory;
use App\Modules\Esp\Integration\Clients\MailerLite\MailerLiteClient;
use App\Modules\Newsletter\Newsletter;
use App\Modules\ReferralProgram\ReferralProgram;
use App\Modules\Reward\Services\Internal\RewardGranter;
use App\Modules\Subscriber\Subscriber;
use App\Modules\Subscriber\SubscriberIsReferral;
use App\Modules\Subscriber\SubscriberStatus;
use Illuminate\Support\Str;
use Tests\TestCase;

final class WebhookEventTest extends TestCase
{
    public function testWhenSubscriberNotExistsFromMailerLite(): void
    {
        // given
        $newsletter = Newsletter::factory()->create();
        $data = $this->prepareData();

        // mock
        $this->mockEspClient(MailerLiteClient::class);


        // when
        $response = $this->postJson("/api/esp/webhook/$newsletter->id", $data);

        // then
        $response->assertOk();
        $this->assertDatabaseHas('subscribers', [
            'newsletter_id' => $newsletter->id,
            'esp_id' => $data['id'],
            'email' => $data['email'],
            'status' => SubscriberStatus::Active,
            'is_referral' => SubscriberIsReferral::No,
        ]);
    }

    public function testWhenSubscriberAlreadyExistsFromMailerLite(): void
    {
        $data = $this->prepareData();
        $newsletter = Newsletter::factory()->create();
        $subscriber = Subscriber::factory()->for($newsletter)->create([
            'email' => $data['email'],
            'status' => SubscriberStatus::Received,
            'is_referral' => SubscriberIsReferral::Yes,
        ]);

        // mock
        $this->mockEspClient(MailerLiteClient::class);


        // when
        $response = $this->postJson("/api/esp/webhook/$newsletter->id", $data);

        // then
        $response->assertOk();
        $this->assertEquals(SubscriberStatus::Active, $subscriber->refresh()->status);
    }

    public function testWhenSubscriberNotExistsFromConvertKit(): void
    {
        // given
        $newsletter = Newsletter::factory()->convertKit()->create();
        $data = [
            'subscriber' => [
                'id' => Str::random(),
                'email_address' => Str::random() . '@test.com',
                'state' => SubscriberStatus::Active->value,
            ]
        ];

        // mock
        $this->mockEspClient(ConvertKitClient::class);

        // when
        $response = $this->postJson("/api/esp/webhook/$newsletter->id", $data);

        // then
        $response->assertOk();
        $this->assertDatabaseHas('subscribers', [
            'newsletter_id' => $newsletter->id,
            'esp_id' => $data['subscriber']['id'],
            'email' => $data['subscriber']['email_address'],
            'status' => SubscriberStatus::Active,
            'is_referral' => SubscriberIsReferral::No,
        ]);
    }

    public function testWrongUuid(): void
    {
        // given
        $data = $this->prepareData();

        // when
        $response = $this->postJson('/api/esp/webhook/abc', $data);

        // then
        $response->assertBadRequest();
        $this->assertEquals('Invalid uuid', $response->json('message'));
    }

    public function testRefererRewardGranterInvokedIfReferer(): void
    {
        $data = $this->prepareData();

        $newsletter = Newsletter::factory()->create();
        ReferralProgram::factory()->for($newsletter)->create();
        $referer = Subscriber::factory()->for($newsletter)->create();
        $subscriber = Subscriber::factory()->for($newsletter)->create([
            'email' => $data['email'],
            'status' => SubscriberStatus::Received,
            'is_referral' => SubscriberIsReferral::Yes,
            'referer_subscriber_id' => $referer->id,
        ]);

        // mock
        $this->mockEspClient(MailerLiteClient::class);

        $rewardGranterMock = $this->mock(RewardGranter::class);
        $rewardGranterMock->expects('grantRewardIfPointsAchieved')->once();

        // when
        $response = $this->postJson("/api/esp/webhook/$newsletter->id", $data);

        // then
        $response->assertOk();
        $this->assertEquals(SubscriberStatus::Active, $subscriber->refresh()->status);
    }

    public function testRefererRewardGranterNotInvokedIfNoReferer(): void
    {
        $data = $this->prepareData();

        $newsletter = Newsletter::factory()->create();
        $subscriber = Subscriber::factory()->for($newsletter)->create([
            'email' => $data['email'],
            'status' => SubscriberStatus::Received,
            'is_referral' => SubscriberIsReferral::Yes,
            'referer_subscriber_id' => null,
        ]);

        // mock
        $this->mockEspClient(MailerLiteClient::class);

        $rewardGranterMock = $this->mock(RewardGranter::class);
        $rewardGranterMock->expects('grantRewardIfPointsAchieved')->times(0);

        // when
        $response = $this->postJson("/api/esp/webhook/$newsletter->id", $data);

        // then
        $response->assertOk();
        $this->assertEquals(SubscriberStatus::Active, $subscriber->refresh()->status);
    }

    public function testRefererRewardGranterNotInvokedIfReferralProgramInactive(): void
    {
        $data = $this->prepareData();

        $newsletter = Newsletter::factory()->create();
        ReferralProgram::factory()->for($newsletter)->create([
            'active' => false,
        ]);
        $referer = Subscriber::factory()->for($newsletter)->create();
        $subscriber = Subscriber::factory()->for($newsletter)->create([
            'email' => $data['email'],
            'status' => SubscriberStatus::Received,
            'is_referral' => SubscriberIsReferral::Yes,
            'referer_subscriber_id' => $referer->id,
        ]);

        // mock
        $this->mockEspClient(MailerLiteClient::class);

        $rewardGranterMock = $this->mock(RewardGranter::class);
        $rewardGranterMock->expects('grantRewardIfPointsAchieved')->times(0);

        // when
        $response = $this->postJson("/api/esp/webhook/$newsletter->id", $data);

        // then
        $response->assertOk();
        $this->assertEquals(SubscriberStatus::Active, $subscriber->refresh()->status);
    }

    private function mockEspClient(string $espClientClassName): void
    {
        $convertKitEspClientMock = $this->mock($espClientClassName);
        $convertKitEspClientMock->shouldReceive('updateSubscriberFields')
            ->once()
            ->andReturn(true);

        $espClientFactoryMock = $this->mock(EspClientFactory::class);
        $espClientFactoryMock->shouldReceive('make')
            ->once()
            ->andReturn($convertKitEspClientMock);
    }

    private function prepareData(): array
    {
        return [
            'id' => Str::random(),
            'email' => Str::random() . '@test.com',
            'status' => SubscriberStatus::Active->value,
        ];
    }
}
