
import subprocess
import sys

# MySQL Executable Path
MYSQL_EXE = r"d:\ATMAJAYA\Semester 6\PBO\Object Presistence\XAMPP\mysql\bin\mysql.exe"
DB_HOST = "localhost"
DB_USER = "root"
DB_NAME = "jariklurik"

def run_query(query):
    cmd = [MYSQL_EXE, "-h", DB_HOST, "-u", DB_USER, f"-D{DB_NAME}", "-e", query]
    result = subprocess.run(cmd, capture_output=True, text=True)
    if result.returncode != 0:
        print("Error:")
        print(result.stderr)
    else:
        print(result.stdout)

query = "SELECT 'users' as 'table', COUNT(*) as 'count' FROM users UNION SELECT 'applicant', COUNT(*) FROM applicant UNION SELECT 'job_vacancy', COUNT(*) FROM job_vacancy;"
run_query(query)
