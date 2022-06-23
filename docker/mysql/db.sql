# create databases
CREATE DATABASE IF NOT EXISTS `lumen_api`;
CREATE DATABASE IF NOT EXISTS `lumen_product`;
CREATE DATABASE IF NOT EXISTS `lumen_order`;

# create root user and grant rights
CREATE USER 'lumen'@'localhost' IDENTIFIED BY 'local';
GRANT ALL PRIVILEGES ON *.* TO 'lumen'@'%';
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%';
