#!/bin/bash

echo Convert MD to PDF
gimli

echo Merge PDF to Manual.pdf
if hash pdftk 2>/dev/null; then
    pdftk *.pdf cat output Manual.pdf
else
    echo "Need pdftk installed but didn't found it."
    while true; do
        read -p "Do you wish to install this program? [y/n] " yn
        case ${yn} in
            [Yy]* ) sudo apt-get install pdftk; break;;
            [Nn]* ) echo "Then please merge the created PDF somehow."; exit;;
            * ) echo "Please answer yes or no.";;
        esac
    done
fi

echo "Remove obsolete PDF."
for FILE in `ls *.md`; do
    rm `basename -s .md ${FILE}`.pdf;
done;
