<?php

namespace App\Repositories;

use App\Models\Note;

class NoteRepository
{
    public function all($userId)
    {
        return Note::where('user_id', $userId)->get();
    }

    public function find($userId, $noteId)
    {
        return Note::where('user_id', $userId)->where('id', $noteId)->first();
    }

    public function create(array $data)
    {
        return Note::create($data);
    }

    public function update($userId, $noteId, array $data)
    {
        $note = $this->find($userId, $noteId);
        if ($note) {
            $note->update($data);
        }
        return $note;
    }

    public function delete($userId, $noteId)
    {
        $note = $this->find($userId, $noteId);
        if ($note) {
            $note->delete();
        }
        return $note;
    }
}
