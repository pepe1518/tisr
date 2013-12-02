#! /bin/bash
lenguaje="java"
ruta=$1
nombre=$2
exten=$3
chmod -R 777 /opt/lampp/htdocs/tisr/
cd /opt/lampp/htdocs/tisr/compilador/data/bin/$ruta/$2

gcc $2 < /opt/lampp/htdocs/tisr/compilador/data/problems/$ruta/input.txt >> salida.txt


chmod -R 777 /opt/lampp/htdocs/tisr/