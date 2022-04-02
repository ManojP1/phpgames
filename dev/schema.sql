drop table appuser cascade;
drop table game cascade;

create table appuser (
	userid varchar(250) primary key,
	password varchar(250) not null,
	skill varchar(50) not null,
	gamePrefs varchar(250)[],
	favcolour varchar(20)
);
create table game (
	gameid serial primary key,
	gameName varchar(250),
	userName varchar(250) REFERENCES appuser(userid),
	outcome varchar(50),
	intVal INTEGER, --special value for each game
	CONSTRAINT game_val CHECK (gameName IN ('Guess Game','15 Puzzle','Peg Solitaire','Mastermind')),
	CONSTRAINT outcome_val CHECK (outcome IN ('win','lose'))
);