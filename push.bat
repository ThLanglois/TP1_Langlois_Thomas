@echo off
REM Demande le message de commit
set /p message=Entrez le message du commit : 

REM Ajoute tous les fichiers
git add .

REM Commit avec le message
git commit -m "%message%"

REM Récupère le nom de la branche locale actuelle
for /f "tokens=*" %%i in ('git rev-parse --abbrev-ref HEAD') do set BRANCH=%%i

REM Vérifie si la branche locale a un upstream
for /f "tokens=*" %%i in ('git rev-parse --abbrev-ref --symbolic-full-name @{u} 2^>nul') do set UPSTREAM=%%i

REM Si pas d'upstream, push avec --set-upstream
if "%UPSTREAM%"=="" (
    echo Pas d'upstream détecté pour la branche %BRANCH%, push avec --set-upstream
    git push --set-upstream origin %BRANCH%
) else (
    git push
)

pause
