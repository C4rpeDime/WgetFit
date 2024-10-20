#!/bin/sh
wget -r -p -np -k -P /www/wwwroot/1.1042.net/work/$2 $1
cp /www/wwwroot/1.1042.net/readme.txt /www/wwwroot/1.1042.net/work/$2/readme.txt
cd /www/wwwroot/1.1042.net/work/$2/
zip -r $2.zip ./
mv $2.zip /www/wwwroot/1.1042.net/down
.rm -rf /www/wwwroot/1.1042.net/work/$2