#!/bin/bash
FILENAME=/opt/backupmysql-`date +%s`.sql
mysqldump –user=root –password=123456 > $FILENAME cbcc_code
            tar -zcf $FILENAME.tar.gz $FILENAME
            
cp -r E:\Development\cbccv2/* /opt/backup            
        
tar -zcvf backup-`date +%s`.tar.gz  /opt/backup/
