#!/bin/bash

# Configuration
SCRIPT_DIR="/home/master/applications/dev/public_html/scripts"
CSV_PATH="/home/master/applications/dev/public_html/docs/Instaquote.csv"
SQL_PATH="$SCRIPT_DIR/rapid_quote_latest.sql"

# Database credentials - GET THESE FROM CLOUDWAYS DASHBOARD
DB_NAME="nzgyhzgduk"
DB_USER="nzgyhzgduk"  
DB_PASS="95sW9Nn9st"
DB_HOST="localhost"

# Log file
LOG_FILE="$SCRIPT_DIR/update.log"

echo "========================================" >> "$LOG_FILE"
echo "$(date): Starting rapid_quote update" >> "$LOG_FILE"

# Check if CSV exists
if [ ! -f "$CSV_PATH" ]; then
    echo "$(date): ERROR - CSV not found at $CSV_PATH" >> "$LOG_FILE"
    exit 1
fi

# Show CSV file age
CSV_AGE=$(stat -c %y "$CSV_PATH" 2>/dev/null || stat -f %Sm "$CSV_PATH")
echo "$(date): CSV last modified: $CSV_AGE" >> "$LOG_FILE"

# Run Python script to generate SQL
/usr/bin/python3 "$SCRIPT_DIR/instaquote_to_sql.py" "$CSV_PATH" "$SQL_PATH" >> "$LOG_FILE" 2>&1

if [ $? -ne 0 ]; then
    echo "$(date): ERROR - Python script failed" >> "$LOG_FILE"
    exit 1
fi

echo "$(date): SQL file generated successfully" >> "$LOG_FILE"

# Execute SQL against database
mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" < "$SQL_PATH" >> "$LOG_FILE" 2>&1

if [ $? -eq 0 ]; then
    echo "$(date): SUCCESS - Database updated" >> "$LOG_FILE"
else
    echo "$(date): ERROR - MySQL execution failed" >> "$LOG_FILE"
    exit 1
fi