CREATE TABLE users (
	`user_id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`first_name` varchar(100) NOT NULL,
	`last_name` varchar(100) NOT NULL,
	`email` varchar(100) NOT NULL UNIQUE
);

CREATE TABLE events (
	`event_code` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`event_name` varchar(100) NOT NULL,
	`registration_fee` int NOT NULL,
	`group_event` int NOT NULL
);

CREATE TABLE groups (
	`g_id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`g_name` varchar(100) NOT NULL,
	`event_code` int NOT NULL,
	FOREIGN KEY (`event_code`) REFERENCES events(`event_code`)
);

CREATE TABLE user_group (
	`g_id` int NOT NULL,
	`user_id` int NOT NULL,
	FOREIGN KEY(`user_id`) REFERENCES users(`user_id`),
	FOREIGN KEY(`g_id`) REFERENCES groups(`g_id`)
);

CREATE TABLE tickets (
	`ticket_id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`event_code` int NOT NULL,
	`amount` int NOT NULL,
	`user_id` int NOT NULL,
	`payment_id` varchar(100) NOT NULL,
	`payment_email` varchar(100) NOT NULL,
	`payment_contact` varchar(100) NOT NULL,
	FOREIGN KEY(`user_id`) REFERENCES users(`user_id`),
	FOREIGN KEY(`event_code`) REFERENCES events(`event_code`)
);

INSERT INTO events(event_name, registration_fee, group_event) 
VALUES
	('vocal_music', '100', '0'),
	('instument_music', '100', '0'),
	('dance', '200', '1');