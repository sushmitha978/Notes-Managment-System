<?php
session_start();
require 'system/config.php'; // Ensure this file connects to your database

if (!isset($_SESSION['id'])) {
    header("Location: access.php?login=true");
    exit;
}

class NoteManager {
    private $mysqli;
    private $logFile = 'app.log';

    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }

    private function log($message) {
        $timestamp = date('Y-m-d H:i:s');
        file_put_contents($this->logFile, "[$timestamp] $message\n", FILE_APPEND);
    }

    public function saveNote($userId, $noteTitle, $noteContent) {
        if (is_null($userId)) {
            $this->log("Attempted to save note with null user ID.");
            return false;
        }

        $stmt = $this->mysqli->prepare("INSERT INTO notes (user_id, note_title, note_content) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $userId, $noteTitle, $noteContent);
        if ($stmt->execute()) {
            $this->log("Note saved for user $userId with title '$noteTitle'.");
            return true;
        } else {
            $this->log("Failed to save note for user $userId: " . $stmt->error);
            return false;
        }
    }

    public function updateNote($userId, $noteId, $noteTitle, $noteContent) {
        $stmt = $this->mysqli->prepare("UPDATE notes SET note_title = ?, note_content = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ssii", $noteTitle, $noteContent, $noteId, $userId);
        if ($stmt->execute()) {
            $this->log("Note updated for user $userId with note ID $noteId and title '$noteTitle'.");
            return true;
        } else {
            $this->log("Failed to update note for user $userId with note ID $noteId: " . $stmt->error);
            return false;
        }
    }

    public function deleteNote($userId, $noteId) {
        $stmt = $this->mysqli->prepare("DELETE FROM notes WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $noteId, $userId);
        if ($stmt->execute()) {
            $this->log("Note deleted for user $userId with note ID $noteId.");
            return true;
        } else {
            $this->log("Failed to delete note for user $userId with note ID $noteId: " . $stmt->error);
            return false;
        }
    }

    public function getUserNotes($userId) {
        $stmt = $this->mysqli->prepare("SELECT id, note_title, note_content FROM notes WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}

$error_msg = '';
$noteManager = new NoteManager($mysqli);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['id'];

    if (isset($_POST['note_id']) && !empty($_POST['note_id'])) {
        // Edit existing note
        $note_id = $_POST['note_id'];
        $note_title = $_POST['note_title'];
        $note_content = $_POST['note_content'];
        if ($noteManager->updateNote($userId, $note_id, $note_title, $note_content)) {
            header("Location: index.php");
            exit;
        } else {
            $error_msg = 'Failed to update note. Please try again.';
        }
    } else if (isset($_POST['save_note'])) {
        // Save new note
        $note_title = $_POST['note_title'];
        $note_content = $_POST['note_content'];
        if ($noteManager->saveNote($userId, $note_title, $note_content)) {
            header("Location: index.php");
            exit;
        } else {
            $error_msg = 'Failed to save note. Please try again.';
        }
    }

    if (isset($_POST['delete_note'])) {
        $note_id = $_POST['note_id'];
        if ($noteManager->deleteNote($userId, $note_id)) {
            header("Location: index.php");
            exit;
        } else {
            $error_msg = 'Failed to delete note. Please try again.';
        }
    }
}

// Fetch notes for display
$notes = $noteManager->getUserNotes($_SESSION["id"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        #editor {
            height: 300px;
        }
    </style>
</head>
<body>

<header class="bg-dark text-white text-center py-3">
    <h1>Notes Management System</h1>
</header>

<div class="container">
    <div class="row">
        <div class="col-md">
            <h2>Manage Notes</h2>
            <?php if (!empty($error_msg)) : ?>
                <div class="alert alert-danger"><?php echo $error_msg; ?></div>
            <?php endif; ?>

            <form action="index.php" method="post">
                <input type="text" name="note_title" placeholder="Note Title" class="form-control mb-3" required>
                <div id="editor"></div>
                <input type="hidden" name="note_content" id="note_content">
                <input type="hidden" name="note_id" id="note_id">
                <button type="submit" name="save_note" class="btn btn-success">Save Note</button>
            </form>

            <h2 class="mt-5">Your Notes</h2>
            <?php if (!empty($notes)) : ?>
                <ul class="list-group">
                    <?php foreach ($notes as $note) : ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><strong><?php echo htmlspecialchars($note['note_title']); ?></strong></span>
                            <div>
                                <button class="btn btn-link p-0 edit-note" data-id="<?php echo $note['id']; ?>" data-title="<?php echo htmlspecialchars($note['note_title']); ?>" data-content="<?php echo htmlspecialchars($note['note_content']); ?>">Edit</button>
                                <form action="index.php" method="post" class="d-inline-block">
                                    <input type="hidden" name="note_id" value="<?php echo $note['id']; ?>">
                                    <button type="submit" name="delete_note" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <p>No notes yet. Start creating!</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    var quill = new Quill('#editor', {
        theme: 'snow'
    });

    document.querySelector('form').onsubmit = function() {
        var noteContent = document.querySelector('#note_content');
        noteContent.value = quill.root.innerHTML;
    };

    document.querySelectorAll('.edit-note').forEach(function(button) {
        button.addEventListener('click', function() {
            var noteId = button.getAttribute('data-id');
            var noteTitle = button.getAttribute('data-title');
            var noteContent = button.getAttribute('data-content');

            document.querySelector('input[name="note_id"]').value = noteId;
            document.querySelector('input[name="note_title"]').value = noteTitle;
            quill.root.innerHTML = noteContent;
        });
    });
</script>
</body>
<footer class="bg-dark text-white text-center py-3 mt-5">
    <p>&copy; <?php echo date('Y'); ?> Notes Management System. All rights reserved.</p>
</footer>
</html>
