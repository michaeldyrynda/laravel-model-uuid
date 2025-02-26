<?php

namespace Tests\Feature;

use Dyrynda\Database\Support\Rules\EfficientUuidExists;
use PHPUnit\Framework\Attributes\Test;
use Ramsey\Uuid\Uuid;
use Tests\Fixtures\EfficientUuidPost;
use Tests\TestCase;

class EfficientUuidExistsRuleTest extends TestCase
{
    #[Test]
    public function it_passes_valid_existing_uuid()
    {
        /** @var \Tests\Fixtures\EfficientUuidPost $post */
        $post = factory(EfficientUuidPost::class)->create();

        $rule = new EfficientUuidExists(EfficientUuidPost::class, 'efficient_uuid');

        $this->assertTrue($rule->passes('efficient_uuid', $post->efficient_uuid));
    }

    #[Test]
    public function it_fails_on_non_existing_uuid()
    {
        $uuid = Uuid::uuid4();

        $rule = new EfficientUuidExists(EfficientUuidPost::class);

        $this->assertFalse($rule->passes('post_id', $uuid));
    }

    #[Test]
    public function it_fails_on_any_non_uuid_invalid_strings()
    {
        $uuid = '1235123564354633';

        $rule = new EfficientUuidExists(EfficientUuidPost::class, 'uuid');

        $this->assertFalse($rule->passes('post_id', $uuid));
    }

    #[Test]
    public function it_works_with_custom_uuid_column_name()
    {
        /** @var \Tests\Fixtures\EfficientUuidPost $post */
        $post = factory(EfficientUuidPost::class)->create();

        $rule = new EfficientUuidExists(EfficientUuidPost::class, 'custom_efficient_uuid');

        $this->assertTrue($rule->passes('custom_efficient_uuid', $post->custom_efficient_uuid));
    }
}
