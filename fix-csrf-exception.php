<?php
$file = 'app/Http/Middleware/VerifyCsrfToken.php';
$content = file_get_contents($file);

if (strpos($content, "'/pcma/voice-fallback'") === false) {
    $pattern = "/(protected \$except = \[)(.*?)(\];)/s";
    $replacement = "$1$2\n        '/pcma/voice-fallback',\n    $3";
    $content = preg_replace($pattern, $replacement, $content);
    
    file_put_contents($file, $content);
    echo "✅ Exception CSRF ajoutée pour /pcma/voice-fallback\n";
} else {
    echo "ℹ️  Exception CSRF déjà présente\n";
}
