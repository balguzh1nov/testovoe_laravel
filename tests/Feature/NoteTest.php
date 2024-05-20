<?php

// tests/Feature/NoteTest.php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Note;

class NoteTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanCreateNote()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        $token = auth()->login($user);

        $response = $this->withHeader('Authorization', "Bearer $token")->postJson('/api/notes', [
            'title' => 'Test Note',
            'content' => 'This is a test note.',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['id', 'title', 'content', 'user_id', 'created_at', 'updated_at']);
    }

    public function testUserCanReadNotes()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        $token = auth()->login($user);
        Note::create(['user_id' => $user->id, 'title' => 'Test Note 1', 'content' => 'Content 1']);
        Note::create(['user_id' => $user->id, 'title' => 'Test Note 2', 'content' => 'Content 2']);

        $response = $this->withHeader('Authorization', "Bearer $token")->getJson('/api/notes');

        $response->assertStatus(200)
                 ->assertJsonCount(2);
    }

    public function testUserCanUpdateNote()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        $token = auth()->login($user);
        $note = Note::create(['user_id' => $user->id, 'title' => 'Test Note', 'content' => 'Content']);

        $response = $this->withHeader('Authorization', "Bearer $token")->putJson("/api/notes/{$note->id}", [
            'title' => 'Updated Test Note',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['id' => $note->id, 'title' => 'Updated Test Note']);
    }

    public function testUserCanDeleteNote()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        $token = auth()->login($user);
        $note = Note::create(['user_id' => $user->id, 'title' => 'Test Note', 'content' => 'Content']);

        $response = $this->withHeader('Authorization', "Bearer $token")->deleteJson("/api/notes/{$note->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Note deleted']);
    }
}
