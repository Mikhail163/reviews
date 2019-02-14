<?php 

class Sql
{

	static function createCommentTable() {
		return 
"
CREATE TABLE IF NOT EXISTS `review` (
  `review_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL DEFAULT '0',
  `customer_id` int(11) NOT NULL DEFAULT '0',
  `review` text NOT NULL,
  `created_on` datetime NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`review_id`),
  KEY `idx_review_customer_id` (`customer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
COMMIT;
";
	}
	
	static function createCustomerTable() {
		return
"
CREATE TABLE IF NOT EXISTS `customer` (
  `customer_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`customer_id`),
  UNIQUE KEY `idx_customer_email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
COMMIT;
";
	}
	
	static function createTestUser() {
		return
"
INSERT INTO `customer` (`name`, `email`) VALUES
('Kruiz Online', 'kruiz@online.ru');
COMMIT;
";
	}
	
   static function getReviews() {
     return 
"
SELECT c.name, r.customer_id, 
	   r.review, r.parent_id,
	   r.created_on, c.email,
       r.review_id
FROM review r
INNER JOIN customer c
	   ON c.customer_id = r.customer_id
ORDER BY r.parent_id, r.review_id
";
   }
   
   static function createNewReview() {
   	return
   	'INSERT INTO review (customer_id, review, created_on, parent_id, page_id)
         VALUES (:customer_id, :review, NOW(), :parent_id, :page_id)';
   }
   
   static function deleteAllReview() {
   	return
   	'TRUNCATE TABLE  review;';
   }
   
}