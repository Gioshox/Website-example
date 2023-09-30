-- Create a database named "website" if it doesn't already exist
CREATE DATABASE IF NOT EXISTS `website`;

-- Create a table named "accounts" to store user account information
CREATE TABLE IF NOT EXISTS `accounts` (
	`id` int(11) NOT NULL AUTO_INCREMENT,  -- Unique identifier for each user
  	`username` varchar(50) NOT NULL,        -- User's username (must not be empty)
  	`password` varchar(255) NOT NULL,       -- User's password (hashed and not empty)
  	`email` varchar(100) NOT NULL,          -- User's email address (must not be empty)
  	`avatar` varchar(255) NOT NULL DEFAULT 'default.png',  -- Avatar filename with a default value
	`admin` tinyint(1) NOT NULL DEFAULT 0,   -- Flag indicating if the user is an admin (0 for no, 1 for yes)
    `activation_code` varchar(50) DEFAULT '', -- Activation code for account activation (default empty)
    PRIMARY KEY (`id`)                      -- Primary key constraint on the "id" column
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
