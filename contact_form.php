<?php
require_once 'db_connection.php';

// Set content type for JSON response
header('Content-Type: application/json');

// Membuat instance database
$database = new Database();
$db = $database->connect();

// Memproses form ketika data dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil dan membersihkan data
    $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $subject = isset($_POST['subject']) ? htmlspecialchars(trim($_POST['subject'])) : '';
    $message = isset($_POST['message']) ? htmlspecialchars(trim($_POST['message'])) : '';
    
    // Validasi input
    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode(['status' => 'error', 'message' => 'Name, email, and message are required fields.']);
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
        exit;
    }
    
    // Validasi panjang input
    if (strlen($name) > 100) {
        echo json_encode(['status' => 'error', 'message' => 'Name is too long (max 100 characters).']);
        exit;
    }
    
    if (strlen($subject) > 200) {
        echo json_encode(['status' => 'error', 'message' => 'Subject is too long (max 200 characters).']);
        exit;
    }
    
    if (strlen($message) > 2000) {
        echo json_encode(['status' => 'error', 'message' => 'Message is too long (max 2000 characters).']);
        exit;
    }
    
    try {
        // Query dengan prepared statement
        $stmt = $db->prepare("INSERT INTO contacts (name, email, subject, message, created_at) 
                            VALUES (:name, :email, :subject, :message, NOW())");
        
        // Bind parameter
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
        $stmt->bindParam(':message', $message, PDO::PARAM_STR);
        
        // Eksekusi query
        if ($stmt->execute()) {
            // Kirim email notifikasi (opsional)
            $to = "baktidwipamungkas@gmail.com"; // Ganti dengan email Anda
            $email_subject = "New Contact Form Submission: " . $subject;
            $email_body = "You have received a new message from $name ($email).\n\n".
                        "Subject: $subject\n\n".
                        "Message:\n$message";
            $headers = "From: $email\r\n";
            $headers .= "Reply-To: $email\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();
            
            // Uncomment the line below if you want to send email notifications
            // mail($to, $email_subject, $email_body, $headers);
            
            // Pastikan hanya mengirim JSON response
            echo json_encode(['status' => 'success', 'message' => 'Message sent successfully! I will get back to you soon.']);
            exit; // Penting: exit setelah mengirim response
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save message. Please try again.']);
            exit;
        }
    } catch(PDOException $e) {
        error_log("Database Error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'An error occurred. Please try again later.']);
        exit;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

// Tutup koneksi
$database->close();
?>