:: работа с датой и временем http://fantasy-portal.ru/blog/mascher/08-december-2009/kak-mozhno-rabotat-s-peremennymi-sredy-date-i-time-v-kommandnyh-faylah
:: определение разрядности http://gedemin.blogspot.ru/2010/11/32-64-bat.html
:: определение ос http://forum.oszone.net/thread-141160-4.html
:: получение ip https://superuser.com/questions/230233/how-to-get-lan-ip-to-a-variable-in-a-windows-batch-file

@echo off

for /F "Tokens=2 Delims=[]" %%i in ('ver') do (
	for /F "Tokens=2,3 Delims=. " %%a in ("%%i") do set version=%%a.%%b
)

If "%version%"=="5.1" (set win=Windows XP)
If "%version%"=="5.2" (set win=Windows XP)
If "%version%"=="6.0" (set win=Windows Vista)
If "%version%"=="6.1" (set win=Windows 7)
If "%version%"=="6.2" (set win=Windows Server 2012)
If "%version%"=="6.3" (set win=Windows Server 2012)

if defined PROCESSOR_ARCHITEW6432 (set arch=x64)
if %PROCESSOR_ARCHITECTURE%==IA64 (set arch=x64)
if %PROCESSOR_ARCHITECTURE%==AMD64 (set arch=x64)
if %PROCESSOR_ARCHITECTURE%==x86 (set arch=x86)

set folder=\\log-server\log$\
::set folder=d:\load\
set curdate=%DATE:~6,4%-%DATE:~3,2%-%DATE:~0,2%
set logfile=%folder%%curdate%.log
set curtime=%TIME:~0,2%:%TIME:~3,2%:%TIME:~6,2%
set tmpfile=%folder%%RANDOM%.log

ipconfig | findstr IPv4 > %tmpfile%
for /F "tokens=14" %%i in (%tmpfile%) do ( 
	set ip=%%i 
)
del %tmpfile% /Q

echo %curdate%;%curtime%;%computername%;%username%;%userdomain%;%win%;%arch%;logon;%ip% >> %logfile%
::pause