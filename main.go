package main

import (
	"fmt"
	"log"
	"net/http"
	"os"
	"time"
)

func main() {
	port := os.Getenv("PORT")
	if port == "" {
		port = "8080"
	}

	http.HandleFunc("/", func(w http.ResponseWriter, r *http.Request) {
		html := `<!DOCTYPE html>
<html>
<head>
    <title>FIT Platform v3.0</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        h1 { font-size: 3em; margin-bottom: 20px; }
        p { font-size: 1.2em; margin: 10px 0; }
        .status { background: rgba(255,255,255,0.1); padding: 20px; border-radius: 10px; max-width: 600px; margin: 20px auto; }
    </style>
</head>
<body>
    <h1>🚀 FIT Platform v3.0</h1>
    <div class="status">
        <p><strong>Successfully deployed on Google Cloud Run!</strong></p>
        <p>Date: %s</p>
        <p>Port: %s</p>
        <p>Ready for configuration with fit.tbhc.uk</p>
    </div>
</body>
</html>`
		
		fmt.Fprintf(w, html, time.Now().Format("2006-01-02 15:04:05"), port)
	})

	log.Printf("🚀 Server starting on port %s", port)
	if err := http.ListenAndServe(":"+port, nil); err != nil {
		log.Fatal(err)
	}
}
