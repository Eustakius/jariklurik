@echo off
cd ci
php spark serve --port 8081 %*
