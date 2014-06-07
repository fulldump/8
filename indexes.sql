# ccBlogItem
ALTER TABLE  `ccBlogItem` CHANGE  `Hash`  `Hash` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ;
ALTER TABLE  `ccBlogItem` ADD INDEX  `Blog` (`Blog`) ;

# ccParseQueue
ALTER TABLE  `ccParseQueue` CHANGE  `Status`  `Status` ENUM('WAITING',  'PROCESSING',  'COMPLETED',  'SERVERDOWN', 'ERROR') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  'WAITING';
ALTER TABLE  `ccParseQueue` CHANGE  `CreatedOn`  `CreatedOn` INT( 11 ) NOT NULL ;
ALTER TABLE  `ccParseQueue` CHANGE  `ProcessedOn`  `ProcessedOn` INT( 11 ) NOT NULL ;
ALTER TABLE  `ccParseQueue` ADD INDEX  `Status` (`Status`) ;

# ccRating
ALTER TABLE  `ccRating` CHANGE  `Up`  `Up` INT( 11 ) NOT NULL ;
ALTER TABLE  `ccRating` CHANGE  `Down`  `Down` INT( 11 ) NOT NULL ;

# SystemSession
ALTER TABLE  `SystemSession` CHANGE  `SessionId`  `SessionId` VARCHAR( 32 ) CHARACTER SET BINARY NOT NULL ;
ALTER TABLE  `SystemSession` ADD UNIQUE  `SessionIdIndex` (  `SessionId` ( 32 ) ) ;

