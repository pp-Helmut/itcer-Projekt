@echo off
if exist node_modules (
gulp --env wp
) else (
echo Projekt nicht ordnungsgemaess eingerichtet!
pause
)
