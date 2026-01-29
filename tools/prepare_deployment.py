
import os
import subprocess
import shutil
import datetime
import zipfile

# Configuration
MYSQL_EXE_DIR = r"d:\ATMAJAYA\Semester 6\PBO\Object Presistence\XAMPP\mysql\bin"
DB_HOST = "localhost"
DB_USER = "root"
DB_PASS = "" 
DB_NAME = "jariklurik"

PROJECT_ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
OUTPUT_DIR = os.path.join(PROJECT_ROOT, "builds")
TIMESTAMP = datetime.datetime.now().strftime("%Y%m%d_%H%M%S")
ZIP_NAME = f"jariklurik_staging_{TIMESTAMP}.zip"
SQL_NAME = f"jariklurik_staging_{TIMESTAMP}.sql"

EXCLUDES = [
    ".git", ".github", ".vscode", "node_modules", "tests", 
    "writable", "tools", "builds", "venv", "__pycache__",
    ".env", "composer.lock", "package-lock.json",
    "jariklurik.sql", "u919724581_jariklurik_prd.sql"
]

# Ensure build directory exists
if not os.path.exists(OUTPUT_DIR):
    os.makedirs(OUTPUT_DIR)

def run_command(cmd, cwd=None):
    print(f"Running: {cmd}")
    try:
        subprocess.run(cmd, shell=True, check=True, cwd=cwd)
    except subprocess.CalledProcessError as e:
        print(f"Command failed: {e}")
        return False
    return True

def build_assets():
    print("--- Building Assets ---")
    # Uncomment if needed, but often simpler to skip if already built
    # run_command("npm run build", cwd=PROJECT_ROOT)
    pass

def export_database():
    print("--- Exporting Database ---")
    mysqldump = os.path.join(MYSQL_EXE_DIR, "mysqldump.exe")
    output_path = os.path.join(OUTPUT_DIR, SQL_NAME)
    
    cmd = [mysqldump, "-h", DB_HOST, "-u", DB_USER, f"-D{DB_NAME}", f"--result-file={output_path}"]
    if DB_PASS:
        cmd.insert(3, f"-p{DB_PASS}")
        
    try:
        subprocess.run(cmd, check=True)
        print(f"Database exported to: {output_path}")
        return True
    except subprocess.CalledProcessError as e:
        print(f"Database export failed: {e}")
        return False

def create_zip():
    print("--- Creating ZIP Package ---")
    zip_path = os.path.join(OUTPUT_DIR, ZIP_NAME)
    
    with zipfile.ZipFile(zip_path, 'w', zipfile.ZIP_DEFLATED) as zipf:
        for root, dirs, files in os.walk(PROJECT_ROOT):
            # Exclude directories
            dirs[:] = [d for d in dirs if d not in EXCLUDES and not any(ex in os.path.join(root, d) for ex in EXCLUDES)]
            
            for file in files:
                file_path = os.path.join(root, file)
                rel_path = os.path.relpath(file_path, PROJECT_ROOT)
                
                # Exclude files
                if any(ex in rel_path for ex in EXCLUDES):
                    continue
                
                zipf.write(file_path, rel_path)
    
    print(f"Package created: {zip_path}")
    return zip_path

def main():
    print("Preparing Staging Deployment...")
    export_database()
    zip_file = create_zip()
    
    print("\n" + "="*30)
    print("✅ DEPLOYMENT PREP COMPLETE")
    print("="*30)
    print(f"1. Database SQL: builds/{SQL_NAME}")
    print(f"2. Application ZIP: builds/{ZIP_NAME}")
    print("\nNext Steps:")
    print("1. Upload ZIP to Hostinger -> public_html (or subdomain folder)")
    print("2. Import SQL to Hostinger Database")
    print("3. Update .env on Hostinger")

if __name__ == "__main__":
    main()
