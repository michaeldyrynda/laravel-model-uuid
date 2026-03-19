<?php

declare(strict_types=1);

use Dyrynda\Database\Support\BinaryUuidBuilder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Uuid;
use Tests\Fixtures\BinaryUuidPost;
use Tests\Fixtures\BinaryUuidProfile;
use Tests\Fixtures\BinaryUuidUser;
use Tests\Fixtures\EfficientUuidPost;

beforeEach(function () {
    // Create test tables with binary UUID columns
    Schema::create('binary_uuid_users', function (Blueprint $table) {
        $table->binary('id', 16)->primary();
        $table->string('name');
    });

    Schema::create('binary_uuid_posts', function (Blueprint $table) {
        $table->id();
        $table->binary('user_id', 16);
        $table->foreign('user_id')->references('id')->on('binary_uuid_users')->onDelete('cascade');
        $table->string('title');
    });

    Schema::create('binary_uuid_profiles', function (Blueprint $table) {
        $table->id();
        $table->binary('user_id', 16)->unique();
        $table->foreign('user_id')->references('id')->on('binary_uuid_users')->onDelete('cascade');
        $table->string('bio');
    });
});

afterEach(function () {
    Schema::dropIfExists('binary_uuid_profiles');
    Schema::dropIfExists('binary_uuid_posts');
    Schema::dropIfExists('binary_uuid_users');
});

it('can create model with binary uuid primary key', function () {
    $user = BinaryUuidUser::create(['name' => 'John Doe']);

    expect($user)
        ->id->not->toBeNull()
        ->id->toBeString();

    expect(Uuid::isValid($user->id))->toBeTrue();
});

it('can query by uuid using where uuid scope', function () {
    $user = BinaryUuidUser::create(['name' => 'Jane Doe']);

    $found = BinaryUuidUser::whereUuid($user->id, 'id')->first();

    expect($found)
        ->not->toBeNull()
        ->id->toEqual($user->id);
});

it('handles belongs to relationship with binary uuid', function () {
    $user = BinaryUuidUser::create(['name' => 'Author']);

    $post = BinaryUuidPost::create([
        'user_id' => $user->id,
        'title' => 'Test Post',
    ]);

    // Lazy loading - refresh to simulate loading from database
    $post = BinaryUuidPost::find($post->id);
    $loadedUser = $post->user;

    expect($loadedUser)
        ->not->toBeNull('belongsTo relationship should work')
        ->id->toEqual($user->id)
        ->name->toEqual('Author');
});

it('handles has many relationship with binary uuid', function () {
    $user = BinaryUuidUser::create(['name' => 'Author']);

    BinaryUuidPost::create(['user_id' => $user->id, 'title' => 'Post 1']);
    BinaryUuidPost::create(['user_id' => $user->id, 'title' => 'Post 2']);
    BinaryUuidPost::create(['user_id' => $user->id, 'title' => 'Post 3']);

    // Refresh to test lazy loading from database
    $user = BinaryUuidUser::find($user->id);
    $posts = $user->posts;

    expect($posts)
        ->toHaveCount(3, 'hasMany relationship should return all related records');

    expect($posts[0])->title->toEqual('Post 1');
    expect($posts[1])->title->toEqual('Post 2');
    expect($posts[2])->title->toEqual('Post 3');
});

it('handles has one relationship with binary uuid', function () {
    $user = BinaryUuidUser::create(['name' => 'John']);

    BinaryUuidProfile::create([
        'user_id' => $user->id,
        'bio' => 'Software developer',
    ]);

    // Refresh to test lazy loading
    $user = BinaryUuidUser::find($user->id);
    $profile = $user->profile;

    expect($profile)
        ->not->toBeNull('hasOne relationship should work')
        ->bio->toEqual('Software developer')
        ->user_id->toEqual($user->id);
});

it('handles eager loading with binary uuid', function () {
    $user1 = BinaryUuidUser::create(['name' => 'User 1']);
    $user2 = BinaryUuidUser::create(['name' => 'User 2']);

    BinaryUuidPost::create(['user_id' => $user1->id, 'title' => 'Post 1.1']);
    BinaryUuidPost::create(['user_id' => $user1->id, 'title' => 'Post 1.2']);
    BinaryUuidPost::create(['user_id' => $user2->id, 'title' => 'Post 2.1']);

    // Eager loading - This uses WHERE IN with multiple UUIDs
    $posts = BinaryUuidPost::with('user')->get();

    expect($posts)->toHaveCount(3);

    $posts->each(function ($post) {
        expect($post)->user->not->toBeNull('Eager loaded user should not be null');
        expect($post)->user->toBeInstanceOf(BinaryUuidUser::class);
    });
});

it('handles reverse eager loading with binary uuid', function () {
    $user1 = BinaryUuidUser::create(['name' => 'User 1']);
    $user2 = BinaryUuidUser::create(['name' => 'User 2']);

    BinaryUuidPost::create(['user_id' => $user1->id, 'title' => 'Post 1.1']);
    BinaryUuidPost::create(['user_id' => $user1->id, 'title' => 'Post 1.2']);
    BinaryUuidPost::create(['user_id' => $user2->id, 'title' => 'Post 2.1']);

    // Eager load posts on users - order by name for deterministic results
    // (binary UUID key ordering differs between SQLite and MySQL)
    $users = BinaryUuidUser::with('posts')->orderBy('name')->get();

    expect($users)->toHaveCount(2);

    expect($users[0])->posts->toHaveCount(2);
    expect($users[1])->posts->toHaveCount(1);
});

it('correctly converts uuid in where in queries', function () {
    $user1 = BinaryUuidUser::create(['name' => 'User 1']);
    $user2 = BinaryUuidUser::create(['name' => 'User 2']);
    $user3 = BinaryUuidUser::create(['name' => 'User 3']);

    // Query with multiple UUIDs
    $found = BinaryUuidUser::whereIn('id', [
        $user1->id,
        $user3->id,
    ])->get();

    expect($found)
        ->toHaveCount(2)
        ->contains('id', $user1->id)->toBeTrue()
        ->contains('id', $user3->id)->toBeTrue()
        ->contains('id', $user2->id)->toBeFalse();
});

it('handles where queries with binary uuid', function () {
    $user = BinaryUuidUser::create(['name' => 'Test User']);

    // Standard WHERE query (not using whereUuid)
    $found = BinaryUuidUser::where('id', $user->id)->first();

    expect($found)
        ->not->toBeNull('WHERE query with UUID string should work')
        ->id->toEqual($user->id);
});

it('does not break non uuid queries', function () {
    $user = BinaryUuidUser::create(['name' => 'John']);

    // Query on non-UUID column should work normally
    $found = BinaryUuidUser::where('name', 'John')->first();

    expect($found)
        ->not->toBeNull()
        ->name->toEqual('John');
});

it('preserves existing where uuid functionality', function () {
    $user = BinaryUuidUser::create(['name' => 'Test']);

    // The existing whereUuid scope should still work
    $found1 = BinaryUuidUser::whereUuid($user->id, 'id')->first();

    // And now regular where should also work
    $found2 = BinaryUuidUser::where('id', $user->id)->first();

    expect($found1)->not->toBeNull();

    expect($found2)
        ->not->toBeNull()
        ->id->toEqual($found1->id);
});

it('handles eager loading with has one relationship', function () {
    $user1 = BinaryUuidUser::create(['name' => 'User 1']);
    $user2 = BinaryUuidUser::create(['name' => 'User 2']);

    BinaryUuidProfile::create(['user_id' => $user1->id, 'bio' => 'Bio 1']);
    BinaryUuidProfile::create(['user_id' => $user2->id, 'bio' => 'Bio 2']);

    $users = BinaryUuidUser::with('profile')->get();

    expect($users)->toHaveCount(2);

    $users->each(function ($user) {
        expect($user)
            ->profile->not->toBeNull('Eager loaded profile should not be null')
            ->profile->toBeInstanceOf(BinaryUuidProfile::class);
    });
});

it('handles where not in with binary uuid', function () {
    $user1 = BinaryUuidUser::create(['name' => 'User 1']);
    $user2 = BinaryUuidUser::create(['name' => 'User 2']);
    $user3 = BinaryUuidUser::create(['name' => 'User 3']);

    // Exclude specific UUIDs
    $found = BinaryUuidUser::whereNotIn('id', [
        $user1->id,
        $user3->id,
    ])->get();

    expect($found)->toHaveCount(1);

    expect($found)
        ->first()->id->toEqual($user2->id)
        ->contains('id', $user1->id)->toBeFalse()
        ->contains('id', $user3->id)->toBeFalse();
});

it('handles where with different operators', function () {
    $user1 = BinaryUuidUser::create(['name' => 'User 1']);
    $user2 = BinaryUuidUser::create(['name' => 'User 2']);

    // Test != operator
    $found = BinaryUuidUser::where('id', '!=', $user1->id)->get();

    expect($found)
        ->toHaveCount(1)
        ->first()->id->toEqual($user2->id);
});

it('handles where with qualified column name', function () {
    $user = BinaryUuidUser::create(['name' => 'Test User']);

    // Query with table.column syntax
    $found = BinaryUuidUser::where('binary_uuid_users.id', $user->id)->first();

    expect($found)
        ->not->toBeNull('WHERE with qualified column name should work')
        ->id->toEqual($user->id);
});

it('handles where with invalid uuid string', function () {
    BinaryUuidUser::create(['name' => 'Valid User']);

    // Query with non-UUID string should not crash, just return no results
    $found = BinaryUuidUser::where('id', 'not-a-valid-uuid')->get();

    expect($found)
        ->toHaveCount(0, 'Invalid UUID should not match any records');
});

it('handles where with non string values', function () {
    $user = BinaryUuidUser::create(['name' => 'Test User']);

    // WHERE with integer value on UUID column - should not crash
    $foundInt = BinaryUuidUser::where('id', 123)->get();
    expect($foundInt)->toHaveCount(0);

    // WHERE with null value
    $foundNull = BinaryUuidUser::where('id', null)->get();
    expect($foundNull)->toHaveCount(0);

    // Normal WHERE on name column should still work
    $foundName = BinaryUuidUser::where('name', 'Test User')->first();
    expect($foundName)->not->toBeNull();
});

it('handles where in with empty array', function () {
    BinaryUuidUser::create(['name' => 'User 1']);
    BinaryUuidUser::create(['name' => 'User 2']);

    // WHERE IN with empty array should return no results
    $found = BinaryUuidUser::whereIn('id', [])->get();

    expect($found)->toHaveCount(0);
});

it('handles where in with mixed valid invalid uuids', function () {
    $user1 = BinaryUuidUser::create(['name' => 'User 1']);
    $user2 = BinaryUuidUser::create(['name' => 'User 2']);

    // Mix of valid UUIDs and invalid strings
    $found = BinaryUuidUser::whereIn('id', [
        $user1->id,
        'not-a-uuid',
        $user2->id,
        'also-not-uuid',
    ])->get();

    // Should only find the valid UUIDs
    expect($found)
        ->toHaveCount(2)
        ->contains('id', $user1->id)->toBeTrue()
        ->contains('id', $user2->id)->toBeTrue();
});

it('handles or where with binary uuid', function () {
    $user1 = BinaryUuidUser::create(['name' => 'User 1']);
    $user2 = BinaryUuidUser::create(['name' => 'User 2']);
    $user3 = BinaryUuidUser::create(['name' => 'User 3']);

    // Query with orWhere
    $found = BinaryUuidUser::where('id', $user1->id)
        ->orWhere('id', $user3->id)
        ->get();

    expect($found)
        ->toHaveCount(2)
        ->contains('id', $user1->id)->toBeTrue()
        ->contains('id', $user3->id)->toBeTrue()
        ->contains('id', $user2->id)->toBeFalse();
});

it('handles where shorthand syntax', function () {
    $user = BinaryUuidUser::create(['name' => 'Test User']);

    // WHERE with 2 arguments (shorthand for =)
    $found = BinaryUuidUser::where('id', $user->id)->first();

    expect($found)
        ->not->toBeNull('WHERE shorthand syntax should work')
        ->id->toEqual($user->id);
});

it('handles complex nested queries', function () {
    $user1 = BinaryUuidUser::create(['name' => 'Admin']);
    $user2 = BinaryUuidUser::create(['name' => 'User']);

    BinaryUuidPost::create(['user_id' => $user1->id, 'title' => 'Admin Post']);
    BinaryUuidPost::create(['user_id' => $user2->id, 'title' => 'User Post']);

    // Complex query with WHERE and relationships
    $posts = BinaryUuidPost::where('title', 'LIKE', '%Post%')
        ->whereIn('user_id', [$user1->id, $user2->id])
        ->with('user')
        ->get();

    expect($posts)->toHaveCount(2);

    $posts->each(function ($post) {
        expect($post)->user->not->toBeNull();
    });
});

it('returns BinaryUuidBuilder from newEloquentBuilder', function () {
    $builder = BinaryUuidUser::query();

    expect($builder)->toBeInstanceOf(BinaryUuidBuilder::class);
});

it('handles closure based where with binary uuid', function () {
    $user1 = BinaryUuidUser::create(['name' => 'User 1']);
    BinaryUuidUser::create(['name' => 'User 2']);

    $found = BinaryUuidUser::where(function ($query) use ($user1) {
        $query->where('id', $user1->id);
    })->first();

    expect($found)
        ->not->toBeNull()
        ->id->toEqual($user1->id);
});

it('handles findOrFail with valid binary uuid', function () {
    $user = BinaryUuidUser::create(['name' => 'Test User']);

    $found = BinaryUuidUser::findOrFail($user->id);

    expect($found)
        ->not->toBeNull()
        ->id->toEqual($user->id);
});

it('throws ModelNotFoundException for findOrFail with non existent uuid', function () {
    BinaryUuidUser::create(['name' => 'Test User']);

    BinaryUuidUser::findOrFail('00000000-0000-0000-0000-000000000000');
})->throws(ModelNotFoundException::class);

it('handles update through binary uuid where clause', function () {
    $user = BinaryUuidUser::create(['name' => 'Original']);

    BinaryUuidUser::where('id', $user->id)->update(['name' => 'Updated']);

    $found = BinaryUuidUser::find($user->id);

    expect($found)->name->toEqual('Updated');
});

it('handles delete through binary uuid where clause', function () {
    $user = BinaryUuidUser::create(['name' => 'To Delete']);

    BinaryUuidUser::where('id', $user->id)->delete();

    expect(BinaryUuidUser::find($user->id))->toBeNull();
});

it('handles whereHas with binary uuid relationships', function () {
    $user1 = BinaryUuidUser::create(['name' => 'Author']);
    BinaryUuidUser::create(['name' => 'Reader']);

    BinaryUuidPost::create(['user_id' => $user1->id, 'title' => 'Post 1']);

    $usersWithPosts = BinaryUuidUser::whereHas('posts')->get();

    expect($usersWithPosts)
        ->toHaveCount(1)
        ->first()->id->toEqual($user1->id);
});

it('handles has count constraint with binary uuid relationships', function () {
    $user1 = BinaryUuidUser::create(['name' => 'Author']);
    BinaryUuidUser::create(['name' => 'Reader']);

    BinaryUuidPost::create(['user_id' => $user1->id, 'title' => 'Post 1']);
    BinaryUuidPost::create(['user_id' => $user1->id, 'title' => 'Post 2']);

    $usersWithMultiplePosts = BinaryUuidUser::has('posts', '>=', 2)->get();

    expect($usersWithMultiplePosts)
        ->toHaveCount(1)
        ->first()->id->toEqual($user1->id);
});

it('handles whereIn on non uuid column normally', function () {
    BinaryUuidUser::create(['name' => 'Alice']);
    BinaryUuidUser::create(['name' => 'Bob']);
    BinaryUuidUser::create(['name' => 'Charlie']);

    $found = BinaryUuidUser::whereIn('name', ['Alice', 'Charlie'])->get();

    expect($found)->toHaveCount(2);
});

it('handles orWhereIn with binary uuid', function () {
    $user1 = BinaryUuidUser::create(['name' => 'User 1']);
    BinaryUuidUser::create(['name' => 'User 2']);
    $user3 = BinaryUuidUser::create(['name' => 'User 3']);

    $found = BinaryUuidUser::where('name', 'nonexistent')
        ->orWhereIn('id', [$user1->id, $user3->id])
        ->get();

    expect($found)
        ->toHaveCount(2)
        ->contains('id', $user1->id)->toBeTrue()
        ->contains('id', $user3->id)->toBeTrue();
});

it('handles uppercase uuid strings in where clause', function () {
    $user = BinaryUuidUser::create(['name' => 'Test User']);
    $uppercaseUuid = strtoupper($user->id);

    $found = BinaryUuidUser::where('id', $uppercaseUuid)->first();

    expect($found)
        ->not->toBeNull()
        ->id->toEqual($user->id);
});

it('does not affect models without UsesBinaryUuidBuilder trait', function () {
    // EfficientUuidPost uses EfficientUuid cast but NOT UsesBinaryUuidBuilder
    $post = EfficientUuidPost::create([
        'title' => 'test post',
        'efficient_uuid' => '8ab48e77-d9cd-4fe7-ace5-a5a428590c18',
    ]);

    $found = EfficientUuidPost::whereUuid('8ab48e77-d9cd-4fe7-ace5-a5a428590c18', 'efficient_uuid')->first();

    expect($found)
        ->not->toBeNull()
        ->efficient_uuid->toEqual('8ab48e77-d9cd-4fe7-ace5-a5a428590c18');
});
