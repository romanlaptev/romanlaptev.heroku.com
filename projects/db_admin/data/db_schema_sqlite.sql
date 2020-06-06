CREATE TABLE IF NOT EXISTS content (
id INTEGER PRIMARY KEY AUTOINCREMENT CHECK (id>= 0), 
-- vid INTEGER NULL CHECK (vid>= 0) DEFAULT NULL, 
-- uid INTEGER NOT NULL DEFAULT 0, 
-- category_id INTEGER NOT NULL DEFAULT 0, 
type_id INTEGER NOT NULL DEFAULT 0, 
title VARCHAR(255) NOT NULL DEFAULT '', 
body_value TEXT NULL DEFAULT NULL, 
body_format INTEGER NOT NULL DEFAULT 1, 
status INTEGER NOT NULL DEFAULT 1, 
created DATETIME NOT NULL DEFAULT 0, 
changed DATETIME NOT NULL DEFAULT 0 
-- language VARCHAR(12) NOT NULL DEFAULT '', 
-- comment INTEGER NOT NULL DEFAULT 0, 
-- promote INTEGER NOT NULL DEFAULT 0, 
-- sticky INTEGER NOT NULL DEFAULT 0, 
-- tnid INTEGER NOT NULL CHECK (tnid>= 0) DEFAULT 0, 
-- translate INTEGER NOT NULL DEFAULT 0
);

-- page, note, book, video, music
CREATE TABLE IF NOT EXISTS content_type ( 
	id INTEGER PRIMARY KEY AUTOINCREMENT CHECK (id>= 0), 
	name VARCHAR(255) NOT NULL DEFAULT '' 
);

-- plain_text Plain text
-- filtered_html Filtered HTML
-- full_html Full HTML 
-- php_code PHP code
CREATE TABLE IF NOT EXISTS filter_format ( 
	id INTEGER PRIMARY KEY AUTOINCREMENT CHECK (id>= 0), 
	format VARCHAR(255) NOT NULL DEFAULT '', 
	name VARCHAR(255) NOT NULL DEFAULT '' 
);

/*
CREATE TABLE IF NOT EXISTS field_data_body (
entity_type VARCHAR(128) NOT NULL DEFAULT '', 
bundle VARCHAR(128) NOT NULL DEFAULT '', 
deleted INTEGER NOT NULL DEFAULT 0, 
entity_id INTEGER NOT NULL CHECK (entity_id>= 0), 
revision_id INTEGER NULL CHECK (revision_id>= 0) DEFAULT NULL, 
language VARCHAR(32) NOT NULL DEFAULT '', 
delta INTEGER NOT NULL CHECK (delta>= 0), 
body_value TEXT NULL DEFAULT NULL, 
body_summary TEXT NULL DEFAULT NULL, 
body_format VARCHAR(255) NULL DEFAULT NULL, 
 PRIMARY KEY (entity_type, entity_id, deleted, delta, language)
);
*/


CREATE TABLE IF NOT EXISTS content_links (
	-- id INTEGER PRIMARY KEY AUTOINCREMENT CHECK (id>= 0), 
	content_id INTEGER NOT NULL CHECK (content_id>= 0) DEFAULT 0,
	parent_id INTEGER NOT NULL CHECK (parent_id>= 0) DEFAULT 0, -- (plid)
	-- menu_id INTEGER NOT NULL CHECK (parent_id>= 0) DEFAULT 0 -- (mlid)
	-- book_id INTEGER NOT NULL CHECK (book_id>= 0) DEFAULT 0 -- (bid)
 PRIMARY KEY (content_id)
);


CREATE TABLE IF NOT EXISTS taxonomy_groups (
	id INTEGER PRIMARY KEY AUTOINCREMENT CHECK (id>= 0), 
	name VARCHAR(255) NOT NULL DEFAULT '',
	description TEXT NULL DEFAULT NULL 
	-- machine_name VARCHAR(255) NOT NULL DEFAULT '', 
	-- hierarchy INTEGER NOT NULL CHECK (hierarchy>= 0) DEFAULT 0, 
	-- module VARCHAR(255) NOT NULL DEFAULT '', 
	-- weight INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS taxonomy_index (
	content_id INTEGER NOT NULL CHECK (content_id>= 0) DEFAULT 0, 
	term_id INTEGER NOT NULL CHECK (term_id>= 0) DEFAULT 0 
	-- sticky INTEGER NULL DEFAULT 0, 
	-- created INTEGER NOT NULL DEFAULT 0
);

/*
CREATE TABLE IF NOT EXISTS taxonomy_term_hierarchy (
	tid INTEGER NOT NULL CHECK (tid>= 0) DEFAULT 0, 
	parent INTEGER NOT NULL CHECK (parent>= 0) DEFAULT 0, 
	 PRIMARY KEY (tid, parent)
);
*/

CREATE TABLE IF NOT EXISTS taxonomy_term_data (
	id INTEGER PRIMARY KEY AUTOINCREMENT CHECK (id>= 0), 
	term_group_id INTEGER NOT NULL CHECK (term_group_id>= 0) DEFAULT 0, 
	name VARCHAR(255) NOT NULL DEFAULT '', 
	parent_id INTEGER NOT NULL CHECK (parent_id>= 0) DEFAULT 0 
	-- description TEXT NULL DEFAULT NULL, 
	-- format VARCHAR(255) NULL DEFAULT NULL, 
	-- weight INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS users (
user_id INTEGER NOT NULL CHECK (user_id>= 0) DEFAULT 0, 
name VARCHAR(60) NOT NULL DEFAULT '', 
password VARCHAR(128) NOT NULL DEFAULT '', 
-- mail VARCHAR(254) NULL DEFAULT '', 
-- theme VARCHAR(255) NOT NULL DEFAULT '', 
-- signature VARCHAR(255) NOT NULL DEFAULT '', 
-- signature_format VARCHAR(255) NULL DEFAULT NULL, 
-- created INTEGER NOT NULL DEFAULT 0, 
-- access INTEGER NOT NULL DEFAULT 0, 
login INTEGER NOT NULL DEFAULT 0, 
-- status INTEGER NOT NULL DEFAULT 0, 
-- timezone VARCHAR(32) NULL DEFAULT NULL, 
-- language VARCHAR(12) NOT NULL DEFAULT '', 
-- picture INTEGER NOT NULL DEFAULT 0, 
-- init VARCHAR(254) NULL DEFAULT '', 
-- data BLOB NULL DEFAULT NULL, 
 PRIMARY KEY (user_id)
);
