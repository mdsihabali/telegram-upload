<?php
// Load environment variables (from Render Dashboard)
$botToken = getenv("TELEGRAM_BOT_TOKEN");
$chat_id  = getenv("TELEGRAM_CHAT_ID");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['document'])) {
    $fileTmpPath = $_FILES['document']['tmp_name'];
    $fileName    = $_FILES['document']['name'];

    if (is_uploaded_file($fileTmpPath)) {
        $url = "https://api.telegram.org/bot$botToken/sendDocument";

        $post_fields = [
            'chat_id'  => $chat_id,
            'document' => new CURLFile($fileTmpPath, mime_content_type($fileTmpPath), $fileName),
            'caption'  => "Uploaded file: $fileName"
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        $result = curl_exec($ch);
        curl_close($ch);

        echo "<pre>Response from Telegram:\n$result</pre>";
    } else {
        echo "File upload failed.";
    }
} else {
    echo "No file selected.";
}
