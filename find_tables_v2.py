
def find_create_tables(filename, outfile):
    outfile.write(f"Scanning {filename}...\n")
    try:
        with open(filename, 'r', encoding='utf-8', errors='ignore') as f:
            for i, line in enumerate(f, 1):
                if "CREATE TABLE" in line:
                    outfile.write(f"{i}: {line.strip()}\n")
    except Exception as e:
        outfile.write(f"Error reading {filename}: {e}\n")

with open('tables_index.txt', 'w') as out:
    find_create_tables('jariklurik.sql', out)
    out.write("-" * 20 + "\n")
    find_create_tables('u919724581_jariklurik_prd.sql', out)
