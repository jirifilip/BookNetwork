@echo off

SET "pathToFile=%cd%"
set autor=holala
echo %pathToFile%

CD cli

php Aiel.php %1 %2 %pathToFile%

CD ..