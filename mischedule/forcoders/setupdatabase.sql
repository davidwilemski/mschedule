
CREATE TABLE divisions( 
	term	VARCHAR(8) 	NOT NULL, 
	name 	VARCHAR(50) 	NOT NULL,
	abbrev 	VARCHAR(8) 	NOT NULL,
	PRIMARY KEY(term, abbrev));

CREATE TABLE courses(
	term 		VARCHAR(8)	NOT NULL,
	division	VARCHAR(8)	NOT NULL,
	number		VARCHAR(8)	NOT NULL,
	name		VARCHAR(50)	NOT NULL,
	PRIMARY KEY(term,division,number));


CREATE TABLE books(
 	term		VARCHAR(8)	NOT NULL,
	division	VARCHAR(8)	NOT NULL,
	course		VARCHAR(8)	NOT NULL,
	sections	vARCHAR(100)	NOT NULL,
	ISBN		VARCHAR(20)	NOT NULL,
	title		VARCHAR(100)	NOT NULL,
	required	INT		NOT NULL,
	PRIMARY KEY(term,division,course,sections,ISBN));

CREATE TABLE sections(
	term		VARCHAR(8)	NOT NULL,
	division	VARCHAR(8)	NOT NULL,
	course		VARCHAR(8)	NOT NULL,
	classNum	VARCHAR(8)	NOT NULL,
	credits		VARCHAR(8)	NOT NULL,
	openSeats	INT		NOT NULL,
	waitlistNum	INT		NOT NULL,
	sectionNum	VARCHAR(8)	NOT NULL,
	sectionType	VARCHAR(16)	NOT NULL,
	instructor	VARCHAR(50)	NOT NULL,
	linkageGroup	INT		NOT NULL,
	PRIMARY KEY(term,classNum));


CREATE TABLE meetings(
	term		VARCHAR(8)	NOT NULL,
	division	VARCHAR(8)	NOT NULL,
	course		VARCHAR(8)	NOT NULL,
	classNum	VARCHAR(8)	NOT NULL,
	startTime	INT		NOT NULL,
	endTime		INT		NOT NULL,
	campus		VARCHAR(8)	NOT NULL,
	PRIMARY KEY(term,classNum,startTime));

CREATE TABLE locations(
	term		VARCHAR(8)	NOT NULL,
	division	VARCHAR(8)	NOT NULL,
	course		VARCHAR(8)	NOT NULL,
	classNum	VARCHAR(8)	NOT NULL,
	timeString	VARCHAR(50)	NOT NULL,
	location	VARCHAR(50)	NOT NULL,
	PRIMARY KEY(term,classNum,timeString,location));

CREATE TABLE wakeys(
	wakey		VARCHAR(50)	NOT NULL,
	lastUsed	INT		NOT NULL,
	PRIMARY KEY(wakey));

CREATE TABLE lastModified(
	term		VARCHAR(8)	NOT NULL,
	division	VARCHAR(8)	NOT NULL,
	course		VARCHAR(8)	NOT NULL,
	lastModified	INT		NOT NULL,
	PRIMARY KEY(term,division,course));
