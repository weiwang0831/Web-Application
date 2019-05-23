CREATE TABLE `restaurant`.`User` ( 
	`email` VARCHAR(100) NOT NULL ,
	`first_name` VARCHAR(100) NULL DEFAULT NULL , 
	`last_name` VARCHAR(100) NULL DEFAULT NULL , 
	`password` VARCHAR(32) NOT NULL , 
	PRIMARY KEY (`email`));

INSERT INTO User() VALUES

CREATE TABLE `restaurant`.`store`(
	`storeID` INT(11) NOT NULL AUTO_INCREMENT,
	`store_name` VARCHAR(100) NULL DEFAULT NULL,
	`like` INT(100) DEFAULT 0,
	PRIMARY KEY (`storeID`)
)

INSERT INTO `store` (`store_name`) VALUES ('Sushi Fuku');
INSERT INTO `store` (`store_name`) VALUES ('Rose Tea');
INSERT INTO `store` (`store_name`) VALUES ('Italian Bravo');
INSERT INTO `store` (`store_name`) VALUES ('Sakura');
INSERT INTO `store` (`store_name`) VALUES ('Everyday Noodle');
INSERT INTO `store` (`store_name`) VALUES ('Vietnam Pho');



CREATE TABLE `restaurant`.`Post` ( 
	`postID` INT(11) NOT NULL AUTO_INCREMENT, 
	`content` TEXT NULL DEFAULT NULL , 
	`like` INT(100) DEFAULT 0 , 
	`dislike` INT(100) DEFAULT 0 , 
	`userID` INT(11) NOT NULL,
	`storeID` INT(11) NOT NULL,
	`Time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`postID`),
	FOREIGN KEY (`UserID`) REFERENCES `User`(`UserID`),
	FOREIGN KEY (`storeID`) REFERENCES `store`(`storeID`));


INSERT INTO `Post` (`content`, `userID`,`storeID`) VALUES ('The sushi is wonderful!', 1,1);
INSERT INTO `Post` (`content`, `userID`,`storeID`) VALUES ('I have been here for 4 times with friends, every italian food here is so authentic', 1,3);
INSERT INTO `Post` (`content`, `userID`,`storeID`) VALUES ('The fish is really fresh!!!!!', 10,1);

CREATE TABLE `restaurant`.`Post` ( 
	`postID` INT(11) NOT NULL AUTO_INCREMENT, 
	`content` TEXT NULL DEFAULT NULL , 
	`email` VARCHAR(100) NOT NULL,
	`storeID` INT(11) NOT NULL,
	`Time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`postID`),
	FOREIGN KEY (`email`) REFERENCES `user`(`email`),
	FOREIGN KEY (`storeID`) REFERENCES `store`(`storeID`));


INSERT INTO `Post` (`content`, `email`,`storeID`) VALUES ('The sushi is wonderful!', 'wei@163.com',1);
INSERT INTO `Post` (`content`, `email`,`storeID`) VALUES ('I have been here for 4 times with friends, every italian food here is so authentic', 'wei@163.com',3);
INSERT INTO `Post` (`content`, `email`,`storeID`) VALUES ('The fish is really fresh!!!!!', 'kiki@163.com',1);


CREATE TABLE `restaurant`.`Post` ( 
	`postID` INT(11) NOT NULL AUTO_INCREMENT, 
	`content` TEXT NULL DEFAULT NULL , 
	`like` INT(100) DEFAULT 0 , 
	`dislike` INT(100) DEFAULT 0 , 
	`email` VARCHAR(100) NOT NULL,
	`storeID` INT(11) NOT NULL,
	`Time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`postID`),
	FOREIGN KEY (`email`) REFERENCES `user`(`email`),
	FOREIGN KEY (`storeID`) REFERENCES `store`(`storeID`));


CREATE TABLE `restaurant`.`LikeTable` ( 
	`postID` INT(11) NOT NULL, 
	`like_email` VARCHAR(100) NOT NULL,
	`like` INT(100) DEFAULT 0 , 
	PRIMARY KEY (`postID`,`like_email`),
	FOREIGN KEY (`like_email`) REFERENCES `user`(`email`),
	FOREIGN KEY (`postID`) REFERENCES `post`(`postID`));

CREATE TABLE `restaurant`.`FavTable` ( 
	`storeID` INT(11) NOT NULL, 
	`fav_email` VARCHAR(100) NOT NULL,
	`fav` INT(100) DEFAULT 0 , 
	PRIMARY KEY (`storeID`,`fav_email`),
	FOREIGN KEY (`fav_email`) REFERENCES `user`(`email`),
	FOREIGN KEY (`storeID`) REFERENCES `store`(`storeID`));

CREATE TABLE `restaurant`.`Tag` ( 
	`postID` INT(11) NOT NULL, 
	`tag_email` VARCHAR(100) NOT NULL,
	`tag_content` VARCHAR(100) NOT NULL ,
	PRIMARY KEY (`tag_content`,`postID`),
	FOREIGN KEY (`tag_email`) REFERENCES `user`(`email`),
	FOREIGN KEY (`postID`) REFERENCES `post`(`postID`));

http://localhost:8012/restaurant_Proj/user_process.php
下一步：sort

echo "<form  METHOD=PUT ACTION=<?php echo $_SERVER['$PHP_SELF'];?>>";
									echo "<label for='name'>Fill in to Review</label><br />";
									echo "<input name='store_name' type='text' value='Enter restaurant name here' style='width:250px;height:40px'/> <br /> ";
									echo "<input name='comment' type='text' value='Enter your comment here' style='width:250px;height:120px'/> <br />";
									echo "<button type='submit' value='Submit' style='width:150px;height:20px'>Submit Review</button>";
									echo "</form>";

# for search
SELECT * FROM post JOIN user USING(email) JOIN store USING(storeID) 
WHERE store_name LIKE '%wei%'
OR first_name LIKE '%wei%'
OR last_name LIKE '%wei%'
OR content LIKE '%wei%';

SELECT * FROM post JOIN user USING(email) JOIN store USING(storeID) WHERE store_name LIKE '%wei%' OR first_name LIKE '%wei%' OR last_name LIKE '%wei%' OR content LIKE '%wei%';


SELECT * FROM `Post` 
JOIN `store` USING (`storeID`) 
JOIN `User` USING (`email`)
JOIN (SELECT `postID`,COALESCE(`No_Like`,0) `No_Like` FROM `Post`
LEFT JOIN (SELECT `postID`, SUM(`like`) AS 'No_Like' FROM liketable GROUP BY `postID`) `A` USING (`postID`)) `B` USING (`postID`)
ORDER BY `Time`, `No_Like` DESC;

#one line
SELECT * FROM `Post` JOIN `store` USING (`storeID`) JOIN `User` USING (`email`) JOIN (SELECT `postID`,COALESCE(`No_Like`,0) `No_Like` FROM `Post` LEFT JOIN (SELECT `postID`, SUM(`like`) AS 'No_Like' FROM liketable GROUP BY `postID`) `A` USING (`postID`)) `B` USING (`postID`) ORDER BY `Time`, `No_Like` DESC;


SELECT * FROM `post` JOIN `user` USING(`email`) JOIN `store` USING(`storeID`) 
JOIN (SELECT `postID`,COALESCE(`No_Like`,0) `No_Like` FROM `Post`
LEFT JOIN (SELECT `postID`, SUM(`like`) AS 'No_Like' FROM liketable GROUP BY `postID`) `A` USING (`postID`)) `B` USING (`postID`)
WHERE `store_name` LIKE '%$input%' OR `first_name` LIKE '%$input%' OR `last_name` LIKE '%$input%' OR `content` LIKE '%$input%'
ORDER BY `Time`, `No_Like` DESC;

SELECT * FROM `post` JOIN `user` USING(`email`) JOIN `store` USING(`storeID`) LEFT JOIN `tag` USING (`postID`) JOIN (SELECT `postID`,COALESCE(`No_Like`,0) `No_Like` FROM `Post` LEFT JOIN (SELECT `postID`, SUM(`like`) AS 'No_Like' FROM liketable 	GROUP BY `postID`) `A` USING (`postID`)) `B` USING (`postID`) WHERE  match(`store_name`) against ('$input')  OR match(`first_name`) against ('$input')  OR match(`last_name`) against ('$input') OR match(`content`) against ('$input') OR match(`tag_content`) against ('$input') ORDER BY `Time`, `No_Like` DESC


ALTER TABLE store ADD FULLTEXT(`store_name`);
ALTER TABLE user ADD FULLTEXT(`first_name`);
ALTER TABLE user ADD FULLTEXT(`last_name`);
ALTER TABLE post ADD FULLTEXT(`content`);
ALTER TABLE tag ADD FULLTEXT(`tag_content`);


#one line
SELECT * FROM `post` JOIN `user` USING(`email`) JOIN `store` USING(`storeID`) JOIN (SELECT `postID`,COALESCE(`No_Like`,0) `No_Like` FROM `Post` LEFT JOIN (SELECT `postID`, SUM(`like`) AS 'No_Like' FROM liketable GROUP BY `postID`) `A` USING (`postID`)) `B` USING (`postID`) WHERE `store_name` LIKE '%$input%' OR `first_name` LIKE '%$input%' OR `last_name` LIKE '%$input%' OR `content` LIKE '%$input%' ORDER BY `Time`, `No_Like` DESC;
SELECT * FROM `post` JOIN `user` USING(`email`) JOIN `store` USING(`storeID`) LEFT JOIN `tag` USING (`postID`) JOIN (SELECT `postID`,COALESCE(`No_Like`,0) `No_Like` FROM `Post` LEFT JOIN (SELECT `postID`, SUM(`like`) AS 'No_Like' FROM liketable GROUP BY `postID`) `A` USING (`postID`)) `B` USING (`postID`) WHERE  match（`store_name`,`first_name`,`last_name`,`content`,`tag_content`）against ('$input') ORDER BY `Time`, `No_Like` DESC

ALTER TABLE LikeTable RENAME COLUMN `email` TO `like_email`;

# insert into like table
INSERT INTO LikeTable (`postID`, `email`, `like`) VALUES (1,'kiki@163.com',1) ON DUPLICATE KEY UPDATE `like`=`like`;


下一步：
写一个用户自己的dashboard page，显示餐馆table，用户可以faviourite
写一个用户的statistics


#calculate restaurant that user has been voted
SELECT storeID,store_name, like_email, COALESCE(No_Like,0)
FROM `store`
LEFT JOIN
(SELECT like_email, `storeID`, `like`,postID, SUM(`like`) `No_Like`
 FROM `post` JOIN `LikeTable` USING (`postID`) JOIN User ON User.email=LikeTable.like_email
WHERE like_email='jie@andrew.cmu.edu' GROUP BY `storeID`,`like_email`,`like`,`postID`) `A` USING (`storeID`)
ORDER BY No_Like DESC

#one line version
SELECT storeID,store_name, like_email, COALESCE(No_Like,0) FROM `store` LEFT JOIN (SELECT like_email, `storeID`, `like`,postID, SUM(`like`) `No_Like` FROM `post` JOIN `LikeTable` USING (`postID`) JOIN User ON User.email=LikeTable.like_email WHERE like_email='jie@andrew.cmu.edu' GROUP BY `storeID`,`like_email`,`like`,`postID`) `A` USING (`storeID`) ORDER BY No_Like DESC

#add new faviourite
INSERT INTO FavTable (`storeID`, `fav_email`, `fav`) VALUES ('$postid','$email',1) ON DUPLICATE KEY UPDATE `fav`=`fav`

#Select all for faviourte Restaurant
SELECT * FROM FavTable JOIN store USING(`storeID`) WHERE fav_email='jie@andrew.cmu.edu'

#dashboard table
SELECT User.`email`, `Last_login`,count(DISTINCT post.`postID`) `posts`,count(DISTINCT FavTable.`storeID`) `favs`,COALESCE(COUNT(DISTINCT LikeTable.`postID`),0) `likes` 
FROM USER LEFT JOIN post USING(`email`)
LEFT JOIN FavTable ON user.`email` = FavTable.`fav_email`
LEFT JOIN LikeTable ON user.`email` = LikeTable.`like_email`
WHERE user.`email`='$email'
GROUP BY post.`email`,`Last_login`


#oneline
SELECT User.`email`, `Last_login`,count(DISTINCT post.`postID`) `posts`,count(DISTINCT FavTable.`storeID`) `favs`,COALESCE(COUNT(DISTINCT LikeTable.`postID`),0) `likes`  FROM USER LEFT JOIN post USING(`email`) LEFT JOIN FavTable ON user.`email` = FavTable.`fav_email` LEFT JOIN LikeTable ON user.`email` = LikeTable.`like_email` WHERE user.`email`='$email' GROUP BY post.`email`,`Last_login`

#alter table to add time stamp for user log in
ALTER TABLE `user` ADD `Last_login` DATETIME NULL DEFAULT NULL ;

#everytime log in, insert the login value
UPDATE `user` SET `Last_login` = CURRENT_TIME() WHERE `email`='jie@andrew.cmu.edu';

#Recommendation table
SELECT DISTINCT A.storeID, A.fav_email 'Afav',B.fav_email 'Bfav',C.storeID 'also_fav_store',D.store_name
FROM favtable A
JOIN favtable B ON A.storeID=B.storeID
JOIN favtable C ON B.fav_email=C.fav_email
JOIN store D ON C.storeID=D.storeID
WHERE A.fav_email!=B.fav_email
AND A.storeID!=C.storeID
ORDER BY A.storeID


#select recommendation here
SELECT  DISTINCT `also_fav_store`, `store_name` FROM recommendation WHERE `also_fav_store`  NOT IN  (SELECT DISTINCT `storeID` FROM recommendation WHERE `Afav`='wei@163.com')



#CREATE RECOMMENDATION TABLE
CREATE TABLE `restaurant`.`Recommendation` ( 
	`storeID` INT(11) NOT NULL,
	`store_name` VARCHAR(100) NULL DEFAULT NULL,
	`tag_email` VARCHAR(100) NOT NULL,
	`tag_content` VARCHAR(100) NOT NULL ,
	PRIMARY KEY (`tag_content`,`postID`),
	FOREIGN KEY (`tag_email`) REFERENCES `user`(`email`),
	FOREIGN KEY (`postID`) REFERENCES `post`(`postID`));




