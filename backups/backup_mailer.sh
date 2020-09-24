#!/bin/bash

#Reference to send email with mutt
now=$(date +%d%m%Y)
filename=$1
backupfilename=$1-$now
docker exec {MYSQL_HOSTNAME} mysqldump -u root --password {MYSQL_ROOT_PASSWORD} {MYSQL_DATABASE} > backup$backupfilename.sql
zip -r backup$backupfilename.zip backup$backupfilename.sql
rm backup$backupfilename.sql
echo "Respaldo de BD - $backupfilename está completado" | mutt -a /backup/backup$backupfilename.zip -s "Database Backup - $backupfilename" undiaenlavidade.cl@gmail.com