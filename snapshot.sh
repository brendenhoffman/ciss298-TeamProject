#!/bin/bash

# Prompt for week number if not provided
if [ -z "$1" ]; then
  read -p "Enter week number (e.g., 1, 2, 3...): " WEEK_NUM
else
  WEEK_NUM=$1
fi

SNAPSHOT_DIR="/var/www/ciss298-TeamProject/week$WEEK_NUM"
ENV_FILE="/var/www/ciss298-TeamProject/.env"

# Read database credentials from .env
DB_NAME=$(grep 'DB_NAME=' "$ENV_FILE" | cut -d '=' -f2)
DB_PASS=$(grep 'DB_PASS=' "$ENV_FILE" | cut -d '=' -f2)
DB_NEW="${DB_NAME}_week$WEEK_NUM"

echo "Creating snapshot for Week $WEEK_NUM..."
echo "Old Database: $DB_NAME"
echo "New Database: $DB_NEW"

# Copy web files
echo "Copying website files..."
cp -r /var/www/ciss298-TeamProject "$SNAPSHOT_DIR"

# Duplicate the database
echo "Copying database..."
mysql -u root -p"$DB_PASS" -e "CREATE DATABASE IF NOT EXISTS $DB_NEW"
TABLE_COUNT=$(mysql -u root -p"$DB_PASS" -D "$DB_NEW" -sse "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='$DB_NEW';")

if [ "$TABLE_COUNT" -eq 0 ]; then
  echo "Database is empty. Importing data..."
  mysqldump -u root -p"$DB_PASS" "$DB_NAME" | mysql -u root -p"$DB_PASS" "$DB_NEW"
else
  echo "Database $DB_NEW already has data. Skipping import to avoid duplication."
fi

# Update .env file in the new folder
echo "Updating .env file..."
sed -i "s/DB_NAME=$DB_NAME/DB_NAME=$DB_NEW/g" "$SNAPSHOT_DIR/.env"

# Push to Git with authentication
cd /var/www/ciss298-TeamProject
git checkout -b "week-$WEEK_NUM"
git add .
git commit -m "Snapshot for Week $WEEK_NUM"
git push origin week-$WEEK_NUM
echo "Snapshot complete. Website is at: ciss298.zenren.xyz/week$WEEK_NUM"
