#!/bin/bash

# Default to port 80 if PORT is not set
PORT=${PORT:-80}

# Update Apache ports configuration
sed -i "s/80/$PORT/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Execute the main container command
exec docker-php-entrypoint apache2-foreground
