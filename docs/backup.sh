#!/bin/bash

mysql_host="127.0.0.1"
mysql_user="root"
mysql_passwd="developer.xu"
#设置月份
month=$(date +%Y-%m)
#sql备份目录
root_dir="/alidata/backup/"
back_dir="/alidata/backup/databases"
data_dir="databases"
store_dir="/database_bk/${month}"
if [ ! -d $back_dir ]; then
    mkdir -p $back_dir
fi
if [ ! -d $root_dir/$store_dir ]; then
    mkdir -p $root_dir/$store_dir
fi
#备份的数据库数组
db_arr=$(echo "show databases;" | mysql -u $mysql_user -p$mysql_passwd -h $mysql_host)
choice_db=("kaipai")
date=$(date +%d)
zipname="backup_${date}.tar.gz"


cd $back_dir

for dbname in ${db_arr}
do

for cdb in ${choice_db}
do
        if [ $dbname == $cdb ]; then
                sqlfile=$dbname-${date}".sql"
                mysqldump -u $mysql_user -p$mysql_passwd -h $mysql_host $dbname >$sqlfile
         fi
done
done

cd ..
tar -zcvf $root_dir/$store_dir/$zipname $data_dir
if [ $? = 0 ]; then
    rm -r $root_dir/$data_dir
fi

#if (! ssh piaoyi@10.252.94.156 "[ -d /data/backup/120_26_124_179/${month} ]")
#then
#   scp -r $root_dir/$store_dir piaoyi@10.252.94.156:/data/backup/120_26_124_179/
#else
#   scp $root_dir/$store_dir/$zipname piaoyi@10.252.94.156:/data/backup/120_26_124_179/${month}/$zipname
#fi
#scp $root_dir/$store_dir/$zipname huiteng@120.26.107.102:/data/backup/120_26_124_179/${month}/$zipname
