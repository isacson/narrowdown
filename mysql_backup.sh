
#!/bin/bash

DB_BACKUP="`date +%Y-%m-%d`"
DB_USER="narrowdo"
DB_PASSWD="25zqK8uPi7"
HN=`hostname | awk -F. '{print $1}'`

# Create the backup directory
mkdir -p $DB_BACKUP

# Remove backups older than 10 days
find /backups/mysql_backup/ -maxdepth 1 -type d -mtime +10 -exec rm -rf {} \;

# Backup each database on the system
for db in $(mysql --user=$DB_USER --password=$DB_PASSWD -e 'show databases' -s --skip-column-names|grep -viE '(staging|performance_schema|information_schema)');
do mysqldump --user=$DB_USER --password=$DB_PASSWD --events --opt --single-transaction $db | gzip > "$DB_BACKUP/mysqldump-$HN-$db-$(date +%Y-%m-%d).gz";
done