@echo off
:: Script pour créer et basculer sur une nouvelle branche Git

set /p branch=Entrez le nom de la nouvelle branche : 

:: Vérifie si un nom a été saisi
if "%branch%"=="" (
    echo Aucun nom de branche spécifié. Annulation.
    pause
    exit /b
)

echo.
echo Creation de la branche "%branch%"...
git checkout -b %branch%

if %errorlevel% neq 0 (
    echo Erreur lors de la creation de la branche.
) else (
    echo.
    echo Branche "%branch%" creee et selectionnee avec succes!
)

pause
