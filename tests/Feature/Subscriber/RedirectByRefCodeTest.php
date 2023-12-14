<?php

declare(strict_types=1);

namespace Feature\Subscriber;

use App\Modules\Subscriber\Subscriber;
use Tests\TestCase;

final class RedirectByRefCodeTest extends TestCase
{
    public function testRedirectByRefCode(): void
    {
        // given
        $existingSubscriber = Subscriber::factory()->create();
        $refCode = $existingSubscriber->ref_code;
        $landingUrl = $existingSubscriber->newsletter->landing_url;

        // when
        $response = $this->get("/join/$refCode");

        // then
        $response->assertRedirect("$landingUrl?reflyteCode=$refCode");
    }

    public function testRedirectByWrongRefCode(): void
    {
        // given
        $existingSubscriber = Subscriber::factory()->create();
        $refCode = $existingSubscriber->ref_code;

        // when
        $response = $this->get("/join/$refCode" . 'x');

        // then
        $response->assertViewIs('invalid-ref-code');
    }
}
