<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Comment;
use App\Models\Blog;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_profile_page(): void
    {
        $response = $this->get(route('profile.show'));

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_profile_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('profile.show'));

        $response->assertStatus(200);
        $response->assertSee($user->name);
        $response->assertSee($user->email);
    }

    public function test_user_can_update_profile_details(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $file = UploadedFile::fake()->create('new-avatar.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Nama Baru',
            'phone' => '08123456789',
            'bio' => 'Ini bio baru saya.',
            'profile_picture' => $file,
        ]);

        $response->assertRedirect(route('profile.show'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertEquals('Nama Baru', $user->name);
        $this->assertEquals('08123456789', $user->phone);
        $this->assertEquals('Ini bio baru saya.', $user->bio);
        $this->assertNotNull($user->profile_picture);

        Storage::disk('public')->assertExists($user->profile_picture);
    }

    public function test_user_cannot_update_profile_with_invalid_data(): void
    {
        $user = User::factory()->create();

        // Nama terlalu panjang (> 50 chars)
        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => str_repeat('A', 51),
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_user_can_change_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('OldPassword123!'),
        ]);

        $response = $this->actingAs($user)->post(route('profile.change-password'), [
            'current_password' => 'OldPassword123!',
            'new_password' => 'NewPassword999$',
            'new_password_confirmation' => 'NewPassword999$',
        ]);

        $response->assertRedirect(route('profile.show'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue(Hash::check('NewPassword999$', $user->password));
    }

    public function test_user_cannot_change_password_with_incorrect_current_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('OldPassword123!'),
        ]);

        $response = $this->actingAs($user)->post(route('profile.change-password'), [
            'current_password' => 'WrongPassword123',
            'new_password' => 'NewPassword999$',
            'new_password_confirmation' => 'NewPassword999$',
        ]);

        $response->assertSessionHasErrors('current_password');
        $this->assertFalse(Hash::check('NewPassword999$', $user->password));
    }

    public function test_user_can_view_comments_history(): void
    {
        $user = User::factory()->create();

        // Let's create a Blog model and a Comment using raw Eloquent as we don't have all factories setup.
        $blog = Blog::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'title' => 'Judul Blog Artikel',
            'slug' => 'judul-blog-artikel',
            'content' => 'Isi blog artikel',
            'status' => 'published',
            'is_visible' => true,
        ]);

        $comment = Comment::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'commentable_id' => $blog->id,
            'commentable_type' => 'App\Models\Blog',
            'content' => 'Komentar saya yang sangat bagus sekali.',
        ]);

        $response = $this->actingAs($user)->get(route('profile.comments'));

        $response->assertStatus(200);
        $response->assertSee('Komentar saya yang sangat bagus sekali.');
        $response->assertSee('Judul Blog Artikel');
    }
}
