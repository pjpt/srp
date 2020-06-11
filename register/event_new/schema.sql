CREATE TABLE users (
	`user_id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`first_name` varchar(200) NOT NULL,
	`last_name` varchar(200) NOT NULL,
	`college_name` varchar(200) NOT NULL,
	`mobile_number` varchar(200) NOT NULL,
	`email` varchar(200) NOT NULL UNIQUE
);

CREATE TABLE events (
	`event_code` varchar(100) NOT NULL PRIMARY KEY,
	`event_name` varchar(200) NOT NULL,
	`price_bmc` int NOT NULL,
	`price_oth` int NOT NULL,
	`max_entries` int NOT NULL
);

CREATE TABLE tickets (
	`ticket_id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`event_code` varchar(100),
	`amount` int NOT NULL,
	`user_id` int NOT NULL,
	`payment_id` varchar(200) NOT NULL,
	`payment_email` varchar(200) NOT NULL,
	`payment_contact` varchar(200) NOT NULL,
	`booking_date` varchar(200) NOT NULL,
	FOREIGN KEY(`user_id`) REFERENCES users(`user_id`),
	FOREIGN KEY(`event_code`) REFERENCES events(`event_code`)
);

CREATE TABLE vibcon_tickets (
	`vibcon_ticket_id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`user_id` int NOT NULL,
	`delcard_id` int,
	`workshop_a` varchar(200),
	`workshop_b` varchar(200),
	`case_presentation` varchar(100),
	`paper_presentation` varchar(100),
	`quiz` varchar(100),
	`symposium` varchar(100),
	`delcard` varchar(100),
	`amount` int NOT NULL,
	`payment_id` varchar(200) NOT NULL,
	`payment_email` varchar(200) NOT NULL,
	`payment_contact` varchar(200) NOT NULL,
	`booking_date` varchar(200) NOT NULL,
	FOREIGN KEY(`user_id`) REFERENCES users(`user_id`),
	FOREIGN kEY(`delcard_id`) REFERENCES delcards(`delcard_id`)
);

CREATE TABLE delcards (
	`delcard_id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`user_id` int NOT NULL,
	`count` int NOT NULL,
	`amount` int NOT NULL,
	`payment_id` varchar(200) NOT NULL,
	`payment_email` varchar(200) NOT NULL,
	`payment_contact` varchar(200) NOT NULL,
	`booking_date` varchar(200) NOT NULL,
	FOREIGN KEY(`user_id`) REFERENCES users(`user_id`)
);

INSERT INTO events(event_code, event_name, price_bmc, price_oth, max_entries) 
VALUES
	('CVOS', 'vocal (solo)', '100', '100', '20'),
	('CVOD', 'vocal (double)', '200', '200', '12'),
	('CVOG', 'vocal (group)', '500', '500', '6'),
	('CINS', 'instrument (solo)', '150', '150', '20'),
	('CING', 'instrument (group)', '100', '100', '6'),
	('CDNS', 'dance (solo)', '50', '100', '30'),
	('CDND', 'dance (double)', '100', '200', '12'),
	('CDNG', 'dance (group)', '300', '450', '20'),
	('LODG', 'oxford style debate', '100', '100', '1000'),
	('LPDG', 'parliamentary debate', '100', '100', '1000'),
	('LEWS', 'eassy writing', '50', '50', '1000'),
	('LPWS', 'poetry writing', '50', '50', '1000'),
	('LTQS', 'trivia quiz', '300', '300', '1000'),
	('LPTS', 'plot twist', '50', '50', '1000'),
	('LOMS', 'open mic', '100', '100', '1000'),
	('LAPS', 'adverticement presentation', '250', '250', '1000'),
	('FPDS', 'pot decoration', '75', '100', '30'),
	('FTSS', 'tshirt making', '75', '100', '40'),
	('FPMS', 'poster making', '30', '50', '45'),
	('FFPS', 'finger paint', '30', '50', '20'),
	('FRNG', 'rangoli', '150', '150', '25'),
	('FPPS', 'pebble painting', '30', '50', '40'),
	('FCLS', 'clay modelling', '50', '70', '25'),
	('FCMG', 'collage making', '10', '100', '15'),
	('SBDMS', 'badminton (man single)', '100', '100', '50'),
	('SBDMD', 'badminton (man double)', '200', '200', '50'),
	('SBDWS', 'badminton (woman single)', '100', '100', '50'),
	('SBDWD', 'badminton (woman double)', '200', '200', '50'),
	('SBDMXD', 'badminton (mixed double)', '200', '200', '50'),
	('SVBG', 'vollyball', '850', '850', '1000'),
	('SCHS', 'chess', '60', '60', '1000'),
	('SCAS', 'carrom', '100', '100', '1000'),
	('STTMS', 'table tennis (man single)', '100', '100', '50'),
	('STTMD', 'table tennis (man double)', '200', '200', '50'),
	('STTWS', 'table tennis (woman single)', '100', '100', '50'),
	('STTWD', 'table tennis (woman double)', '200', '200', '50'),
	('STTMXD', 'table tennis (mixed double)', '100', '100', '50'),
	('SCRG', 'cricket', '2500', '2500', '16'),
	('SATS', 'athletics', '50', '50', '50'),
	('SARS', 'athletics', '100', '100', '50'),
	('SKBG', 'kabaddi', '500', '500', '15'),
	('SBBG', 'basketball', '850', '850', '10'),
	('SFBG', 'football', '2000', '2000', '16');