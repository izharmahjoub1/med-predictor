#!/bin/bash
curl -s -c cookies.txt -X POST "http://localhost:8000/login" \
  -d "email=admin@fifa.com&password=Admin123!&user_type=admin" \
  -H "Content-Type: application/x-www-form-urlencoded"
echo "Login completed, cookies saved to cookies.txt"







