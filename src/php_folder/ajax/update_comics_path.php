<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['new_comics_path'])) {
        $comics_path = $data['new_comics_path'];
        echo json_encode(['success' => true, 'new_path' => $comics_path]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Path not provided']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>