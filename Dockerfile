# Use an official PHP image from Docker Hub
FROM php:8.0-apache

# Copy your application files to the web server directory
COPY . /var/www/html/

# Expose the port Render will route traffic to (Render uses 10000 internally)
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
