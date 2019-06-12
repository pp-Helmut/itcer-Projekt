@echo off
if exist node_modules (
gulp
) else (
echo Projekt nicht ordnungsgemaess eingerichtet!
pause
)
