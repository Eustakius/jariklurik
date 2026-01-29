
import re

def find_create_tables(filename):
    print(f"Scanning {filename}...")
    try:
        with open(filename, 'r', encoding='utf-8', errors='ignore') as f:
            for i, line in enumerate(f, 1):
                if "CREATE TABLE" in line:
                    print(f"{i}: {line.strip()}")
    except Exception as e:
        print(f"Error reading {filename}: {e}")

find_create_tables('jariklurik.sql')
print("-" * 20)
find_create_tables('u919724581_jariklurik_prd.sql')
