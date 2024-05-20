<?php

namespace App\Http\Controllers;

use App\Repositories\NoteRepository;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class NoteController extends Controller
{
    protected $noteRepository;

    public function __construct(NoteRepository $noteRepository)
    {
        $this->noteRepository = $noteRepository;
        $this->middleware('auth:api');
    }

    /**
     * @OA\Get(
     *     path="/api/notes",
     *     summary="Get all notes",
     *     @OA\Response(
     *         response=200,
     *         description="A list of notes",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Test Note"),
     *             @OA\Property(property="content", type="string", example="This is a test note."),
     *             @OA\Property(property="created_at", type="string", example="2024-05-20T14:00:00.000000Z"),
     *             @OA\Property(property="updated_at", type="string", example="2024-05-20T14:00:00.000000Z")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $notes = Cache::remember("notes.user.{$user->id}", 60, function () use ($user) {
            return $this->noteRepository->all($user->id);
        });
        return response()->json($notes);
    }

    /**
     * @OA\Get(
     *     path="/api/notes/{id}",
     *     summary="Get a specific note",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A note",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Test Note"),
     *             @OA\Property(property="content", type="string", example="This is a test note."),
     *             @OA\Property(property="created_at", type="string", example="2024-05-20T14:00:00.000000Z"),
     *             @OA\Property(property="updated_at", type="string", example="2024-05-20T14:00:00.000000Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Note not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Note not found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $note = $this->noteRepository->find($user->id, $id);
        if (!$note) {
            return response()->json(['message' => 'Note not found'], 404);
        }
        return response()->json($note);
    }

    /**
     * @OA\Post(
     *     path="/api/notes",
     *     summary="Create a new note",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","content"},
     *             @OA\Property(property="title", type="string", example="Test Note"),
     *             @OA\Property(property="content", type="string", example="This is a test note.")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Note created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Test Note"),
     *             @OA\Property(property="content", type="string", example="This is a test note."),
     *             @OA\Property(property="created_at", type="string", example="2024-05-20T14:00:00.000000Z"),
     *             @OA\Property(property="updated_at", type="string", example="2024-05-20T14:00:00.000000Z")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $data['user_id'] = $user->id;
        $note = $this->noteRepository->create($data);

        // Очистка кэша после добавления новой заметки
        Cache::forget("notes.user.{$user->id}");

        // Логирование события создания заметки
        Log::info('Note created', ['user_id' => $user->id, 'note_id' => $note->id]);

        return response()->json($note, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/notes/{id}",
     *     summary="Update an existing note",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Updated Test Note"),
     *             @OA\Property(property="content", type="string", example="This is an updated test note.")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Note updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Updated Test Note"),
     *             @OA\Property(property="content", type="string", example="This is an updated test note."),
     *             @OA\Property(property="created_at", type="string", example="2024-05-20T14:00:00.000000Z"),
     *             @OA\Property(property="updated_at", type="string", example="2024-05-20T14:00:00.000000Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Note not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Note not found")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
        ]);

        $note = $this->noteRepository->update($user->id, $id, $data);
        if (!$note) {
            return response()->json(['message' => 'Note not found'], 404);
        }

        // Очистка кэша после обновления заметки
        Cache::forget("notes.user.{$user->id}");

        // Логирование события обновления заметки
        Log::info('Note updated', ['user_id' => $user->id, 'note_id' => $note->id]);

        return response()->json($note);
    }

    /**
     * @OA\Delete(
     *     path="/api/notes/{id}",
     *     summary="Delete a note",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Note deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Note deleted")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Note not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Note not found")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $note = $this->noteRepository->delete($user->id, $id);
        if (!$note) {
            return response()->json(['message' => 'Note not found'], 404);
        }

        // Очистка кэша после удаления заметки
        Cache::forget("notes.user.{$user->id}");

        // Логирование события удаления заметки
        Log::info('Note deleted', ['user_id' => $user->id, 'note_id' => $note->id]);

        return response()->json(['message' => 'Note deleted']);
    }
}
