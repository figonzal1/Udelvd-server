#!/bin/bash

#Reference to send email with mutt
#https://devstudioonline.com/article/take-mysql-database-backup-daily-automatically-in-ubuntu-server-using-cron
#https://ubunlog.com/mutt-cliente-correo-terminal/#Instalar_el_cliente_de_correo_electronico_Mutt
now=$(date +%d%m%Y)
filename=$1
backupfilename=$1-$now
docker exec {MYSQL_HOSTNAME} mysqldump -u root --password={MYSQL_ROOT_PASSWORD} {MYSQL_DATABASE} > backup$backupfilename.sql
zip -r backup$backupfilename.zip backup$backupfilename.sql
rm backup$backupfilename.sql
echo "Respaldo de BD - $backupfilename está completado" | mutt -a /backup/backup$backupfilename.zip -s "Database Backup - $backupfilename" undiaenlavidade.cl@gmail.com