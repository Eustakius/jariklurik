
import os
import subprocess
import sys

# MySQL Executable Path
# Dynamically found or hardcoded based on previous steps
MYSQL_EXE = r"d:\ATMAJAYA\Semester 6\PBO\Object Presistence\XAMPP\mysql\bin\mysql.exe"

# Database Credentials
DB_HOST = "localhost"
DB_USER = "root"
DB_PASS = ""  
DB_NAME = "jariklurik"

# File Paths
SCHEMA_FILE = "jariklurik.sql"
DUMP_FILE = "u919724581_jariklurik_prd.sql"

def run_mysql_script(script_content):
    """Runs a SQL script content via mysql command line tool."""
    cmd = [MYSQL_EXE, "-h", DB_HOST, "-u", DB_USER, f"-D{DB_NAME}"]
    if DB_PASS:
        cmd.insert(4, f"-p{DB_PASS}")
        
    # print(f"Executing SQL script ({len(script_content)} chars)...")
    result = subprocess.run(cmd, input=script_content, capture_output=True, text=True)
    if result.returncode != 0:
        print("Error executing SQL script:")
        print(result.stderr)
        return False
    return True

def get_tables():
    """Returns a list of tables in the database."""
    cmd = [MYSQL_EXE, "-h", DB_HOST, "-u", DB_USER, f"-D{DB_NAME}", "-N", "-e", "SHOW TABLES"]
    if DB_PASS:
        cmd.insert(4, f"-p{DB_PASS}")
    
    result = subprocess.run(cmd, capture_output=True, text=True)
    if result.returncode != 0:
        print("Error getting tables:")
        print(result.stderr)
        return []
    
    return [t.strip() for t in result.stdout.strip().split("\n") if t.strip()]

def drop_all_tables():
    """Drops all tables using a single batch script."""
    print("Dropping all tables...")
    tables = get_tables()
    if not tables:
        print("No tables to drop.")
        return True
        
    lines = ["SET FOREIGN_KEY_CHECKS = 0;"]
    for t in tables:
        lines.append(f"DROP TABLE IF EXISTS `{t}`;")
    lines.append("SET FOREIGN_KEY_CHECKS = 1;")
    
    return run_mysql_script("\n".join(lines))

def truncate_all_tables():
    """Truncates all tables using a single batch script."""
    print("Truncating tables (cleaning test data)...")
    tables = get_tables()
    if not tables:
        print("No tables to truncate.")
        return True
    
    lines = ["SET FOREIGN_KEY_CHECKS = 0;"]
    for t in tables:
        # Using TRUNCATE to reset auto-increment as well
        lines.append(f"TRUNCATE TABLE `{t}`;")
    lines.append("SET FOREIGN_KEY_CHECKS = 1;")
    
    return run_mysql_script("\n".join(lines))

def import_file(filepath):
    """Imports a raw SQL file."""
    print(f"Importing {filepath}...")
    with open(filepath, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()
    return run_mysql_script(content)

def extract_and_import_data(filepath):
    """Extracts INSERT statements handling multi-line constructs and imports them."""
    print(f"Extracting and importing data from {filepath}...")
    
    temp_file = "temp_data_import.sql"
    
    try:
        with open(filepath, 'r', encoding='utf-8', errors='ignore') as infile, \
             open(temp_file, 'w', encoding='utf-8') as outfile:
            
            outfile.write("SET FOREIGN_KEY_CHECKS = 0;\n")
            
            recording = False
            for line in infile:
                stripped = line.strip()
                
                # Start recording if we hit an INSERT
                if stripped.lower().startswith("insert into"):
                    recording = True
                
                if recording:
                    outfile.write(line)
                    # Use a heuristic: if line ends with semicolon, end of statement
                    if stripped.endswith(";"):
                        recording = False
            
            outfile.write("SET FOREIGN_KEY_CHECKS = 1;\n")
            
        return import_file(temp_file)
        
    finally:
        if os.path.exists(temp_file):
            try:
                os.remove(temp_file)
            except:
                pass

def main():
    print("--- Starting Database Migration ---")
    
    # 1. Drop old tables
    if not drop_all_tables():
        sys.exit(1)
        
    # 2. Import New Schema
    if not import_file(SCHEMA_FILE):
        print("Failed to import schema.")
        sys.exit(1)
        
    # 3. Truncate (Process: New schema might contain INSERTs for bad data)
    if not truncate_all_tables():
        print("Failed to truncate tables.")
        sys.exit(1)
        
    # 4. Import Production Data
    if not extract_and_import_data(DUMP_FILE):
        print("Failed to import production data.")
        sys.exit(1)
        
    print("--- Migration Completed Successfully ---")

if __name__ == "__main__":
    main()
