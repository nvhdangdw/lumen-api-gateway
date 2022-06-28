-- opencart
CREATE DATABASE IF NOT EXISTS `opencart`;

-- lumen api gateway
CREATE DATABASE IF NOT EXISTS `lumen_api`;
CREATE DATABASE IF NOT EXISTS `lumen_product`;
CREATE DATABASE IF NOT EXISTS `lumen_order`;

GRANT ALL PRIVILEGES ON *.* TO 'root'@'%';

-- create user and grant rights
CREATE USER 'opencart'@'localhost' IDENTIFIED BY 'opencart';
CREATE USER 'lumen'@'localhost' IDENTIFIED BY 'lumen';
GRANT ALL PRIVILEGES ON *.* TO 'opencart'@'%';
GRANT ALL PRIVILEGES ON *.* TO 'lumen'@'%';
