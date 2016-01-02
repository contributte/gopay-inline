@echo off
%CD%\..\vendor\bin\tester.bat %CD%\cases -s -c %CD%\php-win.ini -j 40 -log %CD%\tester.log %*
rmdir %CD%\tmp /Q /S
