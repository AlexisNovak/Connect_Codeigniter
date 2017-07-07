#!/usr/bin/python

import os, sys
import re
import MySQLdb as mysql

SRC_PATH = 'src'
DB_CONFIG = SRC_PATH + '/application/config/database.php'

SQL_CREATE_TABLE = ('CREATE TABLE `db_evolutions` ('
'`id` int(11) NOT NULL AUTO_INCREMENT,'
'`name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,'
'`version` int(11) NOT NULL,'
'PRIMARY KEY (`id`)'
') ENGINE=MyISAM DEFAULT CHARSET=utf8;')

dbvars = ['hostname', 'username', 'password', 'database']
dbconfig = {}			
db_file = open(DB_CONFIG, 'r')

for line in db_file:
	for var in dbvars:
		if var in line:
			m = re.search(r"\$db\['default'\]\['" + var + "'\] = '(.*)';", line)
			if m :
				dbconfig[var] = m.group(1)

db_file.close()

db = mysql.connect(host=dbconfig['hostname'], user=dbconfig['username'], passwd=dbconfig['password'], db=dbconfig['database'])
db.autocommit(True)
cursor = db.cursor()

def import_sql(version):
	sql_path = 'sql/'
	for file in sorted(os.listdir(sql_path)):
		filename = str(file)
		if re.match(r'^\d+\.sql$', filename):
			new_version = int(filename[0:filename.find('.')]);
			if new_version > version:
				sql = open(sql_path + filename, 'r')
				try:
					statement = " ".join(sql.readlines())
					for s in statement.split(';'):
						s = s.lstrip().rstrip()
						if s != '':
							c = db.cursor()
							c.execute(s)
							c.close()
					c = db.cursor()
					c.execute("INSERT INTO db_evolutions (name, version) VALUES ('" + filename + "'," + str(new_version) + ")")
					c.close()
					print "file %s: ok" % filename
				except mysql.Error as e:
					print "file %s: %s" % (filename, e)
				sql.close()

try:
	cursor.execute("SELECT MAX(version) FROM db_evolutions")
	version = 0
	results = cursor.fetchall()

	for r in results:
		version = r[0]

	import_sql(version)

except mysql.Error as e:
	if e[0] == 1146 :
		cursor.execute(SQL_CREATE_TABLE)
		import_sql(-1)

cursor.close()
db.close()