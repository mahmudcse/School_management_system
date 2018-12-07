DELIMITER $$

DROP PROCEDURE IF EXISTS `getpayments`$$
CREATE PROCEDURE `getpayments` (IN `p_student_id` BIGINT)  begin

	DECLARE cdate DATE;
	DECLARE st DATE;
	DECLARE en DATE;
	DECLARE bDone INT;
	DECLARE v_first_session_id BIGINT;
	DECLARE v_first_class_id BIGINT;
	DECLARE v_session_id BIGINT;
	DECLARE v_class_id BIGINT;
	
	DECLARE curs CURSOR FOR 
		SELECT start, end, class_id, session_id
		FROM enroll e
		INNER JOIN session s ON (s.componentId = e.session_id)
		WHERE student_id = p_student_id;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET bDone = 1;


	DROP TEMPORARY TABLE IF EXISTS tmp_tbl_month;
	CREATE TEMPORARY TABLE IF NOT EXISTS tmp_tbl_month
	(
		id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		tdate DATE
	);
	
	SELECT session_id, class_id INTO v_first_session_id, v_first_class_id
	FROM enroll e
	INNER JOIN session s ON (s.componentId = e.session_id)
	WHERE student_id = p_student_id
	ORDER BY start 
	LIMIT 0, 1;
	
	drop temporary table if exists tmp_tbl_result;
			
	CREATE TEMPORARY TABLE tmp_tbl_result AS
	SELECT f.amount, f.class_id, f.session_id, f.item_id, i.itemName, i.category3, IFNULL(d.unitPrice,0.0) AS paid_amount, DATE_FORMAT(s.`start`, '%m') AS month, DATE_FORMAT(s.`start`, '%Y') as year
	FROM fee_conf f
	INNER JOIN session s ON (s.componentId = f.session_id)
	INNER JOIN item i ON (i.componentId = f.item_id)
	LEFT JOIN transaction_detail d ON (d.itemId = i.componentId AND d.month = DATE_FORMAT(s.`start`,'%m') AND d.year = DATE_FORMAT(s.`start`,'%Y') AND d.accountId = 14 )
	WHERE (i.category2 = 'SCHOOL' OR f.class_id = v_first_class_id) AND i.category3 = 'ONCE' AND f.session_id = v_first_session_id;
	
	OPEN curs;

  	SET bDone = 0;
  	
  	TRUNCATE TABLE tmp_tbl_month;
  	
  	months_loop : LOOP 
	 
		FETCH curs INTO st, en, v_class_id, v_session_id;
		
		IF bDone THEN
        CLOSE curs;
        LEAVE months_loop;
    	END IF;
    	
      
		SET cdate = DATE_FORMAT(st, '%Y-%m-01');
		
		WHILE cdate < en DO
    	 	
			INSERT INTO tmp_tbl_month (tdate) VALUES (cdate);
    	 
    		SET cdate = DATE_ADD(cdate, INTERVAL 1 MONTH);
  	 
		END WHILE;
  
  	END LOOP;
  	
  	INSERT INTO tmp_tbl_result
  	SELECT f.amount, f.class_id, f.session_id, f.item_id, i.itemName, i.category3, IFNULL(d.unitPrice, 0) AS paid_amount, DATE_FORMAT(t.tdate,'%m'), DATE_FORMAT(t.tdate,'%Y')
	FROM fee_conf f
	INNER JOIN item i ON (i.componentId = f.item_id)
	CROSS JOIN tmp_tbl_month t
	LEFT JOIN transaction_detail d ON (d.itemId = i.componentId AND d.month = DATE_FORMAT(t.tdate,'%m') AND d.year = DATE_FORMAT(t.tdate,'%Y') AND d.accountId = 14 )
	WHERE (i.category2 = 'SCHOOL' OR f.class_id = v_class_id) AND i.category3 = 'MONTHLY' AND f.session_id = v_session_id;
  
  	SELECT * FROM tmp_tbl_result ORDER BY year DESC, month;
  	drop temporary table if exists tmp_tbl_month;
	drop temporary table if exists tmp_tbl_result;
end$$

DROP PROCEDURE IF EXISTS `getsequence`$$
CREATE PROCEDURE `getsequence` (IN `seqName` VARCHAR(255))  BEGIN
   DECLARE curVal INT;
   SET curVal = -1;
 	SELECT currentValue INTO curVal FROM sequences WHERE uniqueCode = seqName;
 
   IF curVal = -1 THEN
        INSERt INTO sequences (uniqueCode,currentValue) VALUES(seqName, 0);
   END IF;
   
   UPDATE sequences SET currentValue = currentValue + 1 WHERE uniqueCode = seqName;
   
   SELECT currentValue FROM sequences WHERE uniqueCode = seqName;
END$$


DROP PROCEDURE IF EXISTS `processResult`$$
CREATE PROCEDURE `processResult` (IN `pExamTypeId` INT, IN `pExamId` INT, IN `pCourseId` INT)  
BEGIN
  DECLARE vRule VARCHAR (255);
  DECLARE vTotalMark FLOAT;
  DECLARE vFoundEntry INT;
  
  SELECT concat(' ',rule, ' '), total_mark INTO vRule, vTotalMark  FROM examtype WHERE examType_id = pExamTypeId;
  
  SELECT count(*) INTO vFoundEntry FROM exammark e WHERE e.examtype_id = pExamTypeId AND  e.course_id = pCourseId AND e.exam_id = pExamId;
  
  IF vFoundEntry = 0 THEN
  
    INSERT INTO exammark (student_id, session_id, course_id, exam_id, mark_obtained, examtype_id)
    SELECT em.student_id, em.session_id, em.course_id, em.exam_id, round(SUM(em.mark_obtained) / SUM(et.total_mark) , 2), pExamTypeId
    FROM exammark em
    INNER JOIN examtype et ON (em.examtype_id = et.examtype_id)
    WHERE em.course_id = pCourseId AND em.exam_id = pExamId AND vRule LIKE concat('% ',et.name,' %')
    GROUP BY em.student_id, em.exam_id;
  
  END IF;
  
    UPDATE  exammark e
    INNER JOIN (
      SELECT em.student_id, em.session_id, em.course_id, em.exam_id, SUM(em.mark_obtained) mark_obtained, SUM(et.total_mark) total_mark
      FROM exammark em
      INNER JOIN examtype et ON (em.examtype_id = et.examtype_id)
      WHERE em.course_id = pCourseId AND em.exam_id = pExamId AND vRule LIKE concat('% ',et.name,' %')
      GROUP BY em.student_id, em.exam_id
    ) c ON (e.course_id = c.course_id AND e.exam_id = c.exam_id AND e.student_id = c.student_id)
    SET e.mark_obtained = round(c.mark_obtained*vTotalMark/c.total_mark,2)
    WHERE e.examtype_id = pExamTypeId AND  e.course_id = pCourseId AND e.exam_id = pExamId;
    
    SET @rnk = 0;
 
     UPDATE exammark c 
     INNER JOIN ( 
     
      SELECT e.*,  @rnk := @rnk + 1 as rank 
      FROM exammark e
       WHERE examtype_id = `pExamTypeId` and exam_id = `pExamId` and course_id = `pCourseId`
       order by mark_obtained desc
       
      ) d ON (c.exammark_id = d.exammark_id)
      SET c.position = d.rank;
END$$

DROP PROCEDURE IF EXISTS `publishResult`$$
CREATE PROCEDURE `publishResult` (IN `pExamTypeId` INT, IN `pExamId` INT, IN `pCourseId` INT, IN `pRunning_session` INT, IN `pRunning_term` INT)  
BEGIN
	DELETE FROM publishedmark WHERE examtype_id = pExamTypeId 
    AND exam_id = pExamId AND course_id = pCourseId AND session_id = pRunning_session AND exam_id = pRunning_term;
  INSERT INTO publishedmark (publishedmark_id, student_id, session_id,  course_id,  exam_id,  examtype_id,  
    mark_obtained, is_absent, comment, published_date, lg, gp, position, sgpa, cgpa, 
    group_position, section_position, total_mark) 
  SELECT NULL AS publishedmark_id, student_id, session_id, 
    course_id, exam_id, examtype_id, mark_obtained, 0 AS is_absent, comment, curtime() AS published_date,
    lg, gp, position, sgpa, cgpa, group_position, section_position, total_mark
  FROM  exammark e
  WHERE e.examtype_id = pExamTypeId AND e.exam_id = pExamId 
    AND e.course_id = pCourseId AND session_id = pRunning_session AND exam_id = pRunning_term;
END$$

--
-- Functions
--
DROP FUNCTION IF EXISTS calcGrade$$
CREATE FUNCTION `calcGrade` (`pMark` INTEGER, `pExamtypeId` INTEGER) RETURNS VARCHAR(10) CHARSET utf8 COLLATE utf8_unicode_ci BEGIN
declare vGrade VARCHAR(10);
declare vMark VARCHAR(10);

SELECT total_mark INTO vMark FROM examtype WHERE examtype_id = pExamTypeId;

SELECT name INTO vGrade
FROM grade
WHERE mark_from <= ROUND(pMark*100/vMark)
ORDER BY mark_from DESC
LIMIT 0,1;

RETURN vGrade;
END$$


DROP FUNCTION IF EXISTS calcGradePoint$$
CREATE FUNCTION `calcGradePoint` (`pMark` INTEGER, `pExamtypeId` INTEGER) RETURNS FLOAT BEGIN
declare vGradePoint FLOAT;
declare vMark VARCHAR(10);

SELECT total_mark INTO vMark FROM examtype WHERE examtype_id = pExamTypeId;

SELECT grade_point INTO vGradePoint
FROM grade
WHERE mark_from <= ROUND(pMark*100/vMark)
ORDER BY mark_from DESC
LIMIT 0,1;

RETURN vGradePoint;
END$$

DROP FUNCTION IF EXISTS getCOGS$$
CREATE FUNCTION `getCOGS` (`pItemId` BIGINT) RETURNS FLOAT BEGIN
  declare vRet FLOAT;
  SET vRet = 0.00;
   SELECT SUM(d.quantity*d.unitPrice) / SUM(d.quantity) INTO vRet
	FROM transaction_detail d
	INNER JOIN transaction t ON (t.componentId = d.transactionId AND t.`type` = 'PURCHASE')
	WHERE d.itemId = pItemId AND d.`type` = 1 AND d.accountId = 12;
  RETURN vRet;
END$$

DROP FUNCTION IF EXISTS getCommission$$
CREATE FUNCTION `getCommission` (`pItemId` BIGINT) RETURNS FLOAT BEGIN
  declare vRet FLOAT;
  SET vRet = 0.00;
   SELECT SUM(d.`type`*d.quantity*d.unitPrice) / SUM(d.`type`*d.quantity) INTO vRet
	FROM transaction_detail d
	WHERE d.itemId = pItemId AND d.accountId = 7;
  RETURN vRet;
END$$

DROP FUNCTION IF EXISTS highestMark$$
CREATE FUNCTION `highestMark` (`pExamId` INTEGER, `pCourseId` INTEGER, `pExamtypeId` INTEGER) RETURNS FLOAT BEGIN
declare vHighestMark FLOAT;
SET vHighestMark = 0.0;

SELECT MAX(mark_obtained) INTO vHighestMark 
FROM exammark WHERE examtype_id = pExamTypeId AND exam_id = pExamId AND course_id = pCourseId;

RETURN vHighestMark;
END$$
 
DROP FUNCTION IF EXISTS lastPurchasePrice$$
CREATE FUNCTION `lastPurchasePrice` (`itemId` INT) RETURNS DOUBLE BEGIN
	DECLARE v_tdate DATETIME;
	DECLARE v_price DOUBLE;
	SET v_price = 0.0;
	SELECT MAX(tdate) INTO v_tdate 
	FROM transaction_detail d
	INNER JOIN transaction t ON (d.transactionId = t.componentId)
	WHERE t.type = 'PURCHASE' AND d.itemId = itemId;
	SELECT MAX(unitPrice) INTO v_price
	FROM transaction_detail d
	INNER JOIN transaction t ON (d.transactionId = t.componentId)
	WHERE t.type = 'PURCHASE' AND d.itemId = itemId AND t.tdate = v_tdate;
	RETURN v_price;
END$$

DELIMITER ;

-- --------------------------------------------------------


--
-- Table structure for table `academic_calendar`
--

DROP TABLE IF EXISTS `academic_calendar`;
CREATE TABLE IF NOT EXISTS `academic_calendar` (
  `ac_calendar_id` int(11) NOT NULL AUTO_INCREMENT,
  `event` varchar(255) NOT NULL,
  `event_type` varchar(50) NOT NULL,
  `recurring` varchar(50) NOT NULL,
  `start_date` varchar(30) NOT NULL,
  `end_date` varchar(30) NOT NULL,
  `class_off` tinyint(1) NOT NULL,
  `school_off` tinyint(1) NOT NULL,
  PRIMARY KEY(`ac_calendar_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `academic_syllabus`
--
DROP TABLE IF EXISTS `academic_syllabus`;
CREATE TABLE IF NOT EXISTS `academic_syllabus` (
  `academic_syllabus_id` int(11) NOT NULL AUTO_INCREMENT,
  `academic_syllabus_code` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `title` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `class_id` int(11) NOT NULL,
  `uploader_type` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `uploader_id` int(11) NOT NULL,
  `year` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `file_name` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY(`academic_syllabus_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `account`
--
DROP TABLE IF EXISTS `account`;
CREATE TABLE IF NOT EXISTS `account` (
  `componentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `uniqueCode` varchar(128) DEFAULT NULL,
  `description` varchar(128) DEFAULT NULL,
  `category1` varchar(128) DEFAULT NULL,
  `category2` varchar(128) DEFAULT NULL,
  `category3` varchar(128) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `version` int(11) DEFAULT '0',
  `createddate` datetime DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY(`componentId`)
);

INSERT INTO `account` (`componentId`, `uniqueCode`, `description`, `category1`, `category2`, `category3`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES
(1, 'Cash in Hand', 'CASH', 'ASSET', 'CURRENT ASSET', 'CASH', 0, 0, NULL, NULL, NULL, NULL),
(12, 'PURCHASE', 'PURCHASE', 'EXPENSE', '', '', 0, 0, NULL, NULL, NULL, NULL),
(14, 'STUDENT FEE', 'STUDENT FEE', 'REVENUE', 'FEE', '', 0, 0, NULL, NULL, NULL, NULL),
(13, 'SALE', 'SALE', 'REVENUE', 'REVENUE FROM SALE', '', 0, 0, NULL, NULL, NULL, NULL),
(5, 'PAYABLE', 'PAYABLE', 'LIABILITY', '', '', 0, 0, NULL, NULL, NULL, NULL),
(4, 'RECEIVABLE', 'RECEIVABLE', 'ASSET', '', '', 0, 0, NULL, NULL, NULL, NULL),
(8, 'PURCHASE COMMISSION', 'PURCHASE COMMISSION', 'EXPENSE', '', '', 0, 0, NULL, NULL, NULL, NULL),
(9, 'SALE COMMISSION', 'SALE DISCOUNT', 'EXPENSE', 'OPERATING EXPENSE', '', 0, 0, NULL, NULL, NULL, NULL),
(10, 'CASH ADVANCE', 'CASH ADVANCE', 'ASSET', '', '', 0, 0, NULL, NULL, NULL, NULL),
(7, 'SUSPENSION', 'SUSPENSION', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--
DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `level` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `authentication_key` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY(`admin_id`)
);

INSERT INTO `admin` (`admin_id`, `name`, `email`, `password`, `level`, `authentication_key`)
VALUES
(1, 'Mr. Admin', 'admin@oisd.info', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '1', '');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--
DROP TABLE IF EXISTS `attendance`;
CREATE TABLE IF NOT EXISTS `attendance` (
  `attendance_id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` datetime NOT NULL,
  `year` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `class_id` int(11) NULL,
  `section_id` int(11) NULL,
  `student_id` int(11) NOT NULL,
  `class_routine_id` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0(undefined) 1(present) 2(absent)',
  `course_id` int(11) DEFAULT NULL,
  PRIMARY KEY(`attendance_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `attendance_backup`
--
DROP TABLE IF EXISTS `attendance_backup`;
CREATE TABLE IF NOT EXISTS `attendance_backup` (
  `attendance_id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL COMMENT '0 undefined , 1 present , 2  absent',
  `student_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `year` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `session` longtext NOT NULL,
  PRIMARY KEY(`attendance_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `book`
--
DROP TABLE IF EXISTS `book`;
CREATE TABLE IF NOT EXISTS `book` (
  `book_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `author` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `class_id` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `price` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY(`book_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `campus`
--
DROP TABLE IF EXISTS `campus`;
CREATE TABLE IF NOT EXISTS `campus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campus_name` varchar(255) NOT NULL,
  `institute_name` varchar(255) NOT NULL,
  PRIMARY KEY(`id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--
DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY(`id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `class`
--
DROP TABLE IF EXISTS `class`;
CREATE TABLE IF NOT EXISTS `class` (
  `class_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `campus_id` int(11) NOT NULL,
  `name_numeric` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY(`class_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `class_group`
--
DROP TABLE IF EXISTS `class_group`;
CREATE TABLE IF NOT EXISTS `class_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) NOT NULL,
  PRIMARY KEY(`id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `class_routine`
--
DROP TABLE IF EXISTS `class_routine`;
CREATE TABLE IF NOT EXISTS `class_routine` (
  `class_routine_id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `time_start` int(11) NOT NULL,
  `time_end` int(11) NOT NULL,
  `time_start_min` int(11) NOT NULL,
  `time_end_min` int(11) NOT NULL,
  `day` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `year` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY(`class_routine_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `codes`
--
DROP TABLE IF EXISTS `codes`;
CREATE TABLE IF NOT EXISTS `codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key_name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY(`id`)
);


INSERT INTO `codes` (`id`, `key_name`, `value`) VALUES
(1, 'fee.type', 'Yearly'),
(2, 'fee.type', 'Monthly'),
(3, 'fee.type', 'Once'),
(4, 'fee.category', 'School'),
(5, 'fee.category', 'Class'),
(6, 'fee.category', 'Group'),
(7, 'fee.category', 'Student'),
(8, 'event.type', 'holiday'),
(9, 'event.type', 'celebration'),
(10, 'event.type', 'academic'),
(11, 'recurring.type', 'Once'),
(12, 'recurring.type', 'Every_Year'),
(13, 'item.category2', 'CLASS'),
(14, 'item.category2', 'SCHOOL'),
(15, 'item.category2', 'GROUP'),
(16, 'item.category3', 'MONTHLY'),
(17, 'item.category3', 'ONCE'),
(18, 'item.category3', 'SESSION'),
(21, 'notification.sms.absent.content', 'Your child is absent today.'),
(22, 'notification.sms.absent.title', 'Absent notification'),
(23, 'group', 'Group'),
(24, 'class', 'Class'),
(25, 'teacher', 'Class Teacher\'s Signature'),
(26, 'head', 'Principal\'s Signature'),
(36, 'guardian', 'Guardian\'s Signature'),
(27, 'notification.sms.fee.content', 'Received Taka'),
(28, 'notification.sms.fee.title', 'Fee Collection notification'),
(29, 'notification.sms.exammark.title', 'Exammark notification'),
(30, 'notification.sms.noticeboard.title', 'Noticeboard'),
(31, 'notification.sms.notification.title', 'ENotification'),
(32, 'signature.reciept.student', 'Student'),
(33, 'signature.bank', 'Mannager'),
(38, 'signature.reciept.teacher', 'Class Teacher'),
(39, 'signature.reciept.headmaster', 'Head Master');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--
DROP TABLE IF EXISTS `course`;
CREATE TABLE IF NOT EXISTS `course` (
  `course_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `unique_code` int(11) NULL,
  `class_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `tittle` longtext COLLATE utf8_unicode_ci NOT NULL,
  `is_optional` tinyint(1) NOT NULL DEFAULT '0',
  `credit` int(1) NOT NULL,
  `combined` int(11) NULL
);


-- Table structure for table `course_group`

CREATE TABLE `course_group` (
  `componentId` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  PRIMARY KEY(`componentId`)
);

-- --------------------------------------------------------

--
-- Table structure for table `courseconfig`
--
DROP TABLE IF EXISTS `courseconfig`;
CREATE TABLE IF NOT EXISTS `courseconfig` (
  `courseconfig_id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  PRIMARY KEY(`courseconfig_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `courseteacherassignment`
--
DROP TABLE IF EXISTS `courseteacherassignment`;
CREATE TABLE IF NOT EXISTS `courseteacherassignment` (
  `cta_id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_id` int(7) NOT NULL,
  `course_id` int(7) NOT NULL,
  `class_id` int(5) NOT NULL,
  `session_id` int(3) NOT NULL,
  PRIMARY KEY(`cta_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--
DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `componentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `uniqueCode` varchar(128) DEFAULT NULL,
  `name` char(50) NOT NULL,
  `customergroup` varchar(255) NULL,
  `customertype` varchar(255) NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` char(100) NOT NULL,
  `phone` char(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `isCustomer` bit(1) DEFAULT b'0',
  `isSupplier` bit(1) DEFAULT b'0',
  `isOwn` bit(1) DEFAULT b'0',
  `status` int(11) NOT NULL DEFAULT '0',
  `version` int(11) DEFAULT '0',
  `createddate` datetime DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY(`componentId`)
);

-- --------------------------------------------------------

--
-- Table structure for table `document`
--
DROP TABLE IF EXISTS `document`;
CREATE TABLE IF NOT EXISTS `document` (
  `documentId` int(11) NOT NULL AUTO_INCREMENT,
  `uniqueCode` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `createdBy` int(11) DEFAULT '0',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '0',
  `class_id` int(11) NOT NULL,
  `group_id` int(2) NOT NULL,
  `file_name` longtext,
  `file_type` varchar(10) NOT NULL,
  `timestamp` varchar(30) NOT NULL,
  PRIMARY KEY(`documentId`)
);

-- --------------------------------------------------------

--
-- Table structure for table `documentpermission`
--
DROP TABLE IF EXISTS `documentpermission`;
CREATE TABLE IF NOT EXISTS `documentpermission` (
  `permissionId` int(11) NOT NULL AUTO_INCREMENT,
  `documentId` int(11) NOT NULL DEFAULT '0',
  `type` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `shareToId` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY(`permissionId`)
);

-- --------------------------------------------------------

--
-- Table structure for table `dormitory`
--
DROP TABLE IF EXISTS `dormitory`;
CREATE TABLE IF NOT EXISTS `dormitory` (
  `dormitory_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext COLLATE utf8_unicode_ci NOT NULL,
  `number_of_room` longtext COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY(`dormitory_id`)
);

DROP TABLE IF EXISTS `employee_salary`;
CREATE TABLE `employee_salary` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `salary` INT(11) NULL DEFAULT NULL,
  `applicableFrom` DATETIME NULL DEFAULT NULL,
  `applicableTill` DATETIME NULL DEFAULT NULL,
  `description` LONGTEXT NULL,
  PRIMARY KEY (`id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `enroll`
--
DROP TABLE IF EXISTS `enroll`;
CREATE TABLE IF NOT EXISTS `enroll` (
  `enroll_id` int(11) NOT NULL AUTO_INCREMENT,
  `enroll_code` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `student_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `roll` int(11) NOT NULL,
  `date_added` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `session_id` bigint(20) NOT NULL,
  `year` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY(`enroll_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `exam`
--
DROP TABLE IF EXISTS `exam`;
CREATE TABLE IF NOT EXISTS `exam` (
  `exam_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `date` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `year` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `comment` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY(`exam_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `examcourse`
--
DROP TABLE IF EXISTS `examcourse`;
CREATE TABLE IF NOT EXISTS `examcourse` (
  `examcourse_id` int(11) NOT NULL AUTO_INCREMENT,
  `examtype_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `exam_id` int(11) DEFAULT NULL,
  `order_index` int(11) DEFAULT NULL,
  `report_card` bit(1) DEFAULT b'0',
  `passing_check` bit(1) DEFAULT NULL,
  PRIMARY KEY(`examcourse_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `exammark`
--
DROP TABLE IF EXISTS `exammark`;
CREATE TABLE IF NOT EXISTS `exammark` (
  `exammark_id` INT(11) NOT NULL AUTO_INCREMENT,
  `student_id` INT(11) NOT NULL,
  `session_id` INT(11) NULL DEFAULT NULL,
  `course_id` INT(11) NOT NULL,
  `exam_id` INT(11) NOT NULL,
  `examtype_id` INT(11) NOT NULL,
  `mark_obtained` INT(11) NOT NULL DEFAULT '0',
  `attendance` INT(11) NOT NULL DEFAULT '0',
  `comment` LONGTEXT NULL COLLATE 'utf8_unicode_ci',
  `lg` VARCHAR(50) NULL DEFAULT NULL,
  `gp` FLOAT NULL DEFAULT NULL,
  `position` INT(11) NULL DEFAULT NULL,
  `sgpa` FLOAT NULL DEFAULT NULL,
  `cgpa` FLOAT NULL DEFAULT NULL,
  `group_position` INT(11) NULL DEFAULT NULL,
  `section_position` INT(11) NULL DEFAULT NULL,
  `total_mark` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`exammark_id`)
);


-- --------------------------------------------------------

--
-- Table structure for table `examtype`
--
DROP TABLE IF EXISTS `examtype`;
CREATE TABLE IF NOT EXISTS `examtype` (
  `examtype_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `rule` longtext COLLATE utf8_unicode_ci NOT NULL,
  `displayname` longtext COLLATE utf8_unicode_ci NOT NULL,
  `total_mark` int(11) NOT NULL,
  PRIMARY KEY(`examtype_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `expense_category`
--
DROP TABLE IF EXISTS `expense_category`;
CREATE TABLE IF NOT EXISTS `expense_category` (
  `expense_category_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY(`expense_category_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `feeconfig`
--
DROP TABLE IF EXISTS `feeconfig`;
CREATE TABLE IF NOT EXISTS `feeconfig` (
  `fee_id` int(11) NOT NULL AUTO_INCREMENT,
  `fee_name` varchar(255) NOT NULL,
  `fee_type` varchar(255) NOT NULL,
  `fee_category` varchar(255) NOT NULL,
  `fee_amount` int(11) NOT NULL,
  PRIMARY KEY(`fee_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `fee_conf`
--
DROP TABLE IF EXISTS `fee_conf`;
CREATE TABLE IF NOT EXISTS `fee_conf` (
  `componentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `amount` float DEFAULT NULL,
  `class_id` bigint(20) DEFAULT NULL,
  `group_id` bigint(20) DEFAULT NULL,
  `student_id` bigint(20) DEFAULT NULL,
  `session_id` bigint(20) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  PRIMARY KEY(`componentId`)
);

-- --------------------------------------------------------

--
-- Table structure for table `fee_record`
--
DROP TABLE IF EXISTS `fee_record`;
CREATE TABLE IF NOT EXISTS `fee_record` (
  `fee_record_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `receipt_no` varchar(11) NOT NULL,
  PRIMARY KEY(`fee_record_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `functions`
--
DROP TABLE IF EXISTS `functions`;
CREATE TABLE IF NOT EXISTS `functions` (
  `function_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `is_group` tinyint(4) NOT NULL DEFAULT '0',
  `function` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `display_name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `admin` tinyint(4) DEFAULT '0',
  `parent_id` bigint(20) DEFAULT '0',
  `teacher` tinyint(4) DEFAULT '0',
  `parent` tinyint(4) DEFAULT '0',
  `student` tinyint(4) DEFAULT '0',
  PRIMARY KEY(`function_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `grade`
--
DROP TABLE IF EXISTS `grade`;
CREATE TABLE IF NOT EXISTS `grade` (
  `grade_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `grade_point` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `mark_from` int(11) NOT NULL,
  `mark_upto` int(11) NOT NULL,
  `comment` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY(`grade_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--
DROP TABLE IF EXISTS `invoice`;
CREATE TABLE IF NOT EXISTS `invoice` (
  `invoice_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `title` longtext COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `amount` int(11) NOT NULL,
  `amount_paid` longtext COLLATE utf8_unicode_ci NOT NULL,
  `due` longtext COLLATE utf8_unicode_ci NOT NULL,
  `creation_timestamp` int(11) NOT NULL,
  `payment_timestamp` longtext COLLATE utf8_unicode_ci NOT NULL,
  `payment_method` longtext COLLATE utf8_unicode_ci NOT NULL,
  `payment_details` longtext COLLATE utf8_unicode_ci NOT NULL,
  `status` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'paid or unpaid',
  `year` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY(`invoice_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `item`
--
DROP TABLE IF EXISTS `item`;
CREATE TABLE IF NOT EXISTS `item` (
  `componentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `uniqueCode` varchar(128) DEFAULT NULL,
  `itemName` char(75) NOT NULL,
  `category1` varchar(128) DEFAULT NULL,
  `category2` varchar(128) DEFAULT NULL,
  `category3` varchar(128) DEFAULT NULL,
  `salePrice` float DEFAULT NULL,
  `minQty` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `version` int(11) DEFAULT '0',
  `createddate` datetime DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY(`componentId`)
);

INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES 
('1', 'Cash/OTHERS//', 'Cash', 'OTHERS', NULL, NULL, NULL, 1, '0', '0', NULL, NULL, NULL, NULL),
('3', 'INVENTORY/RFL/Dine/Chair', 'Chair', 'INVENTORY', 'RFL', 'Dine', 100, 10, 0, 0, NULL, NULL, NULL, NULL),
('2', 'Taka/INVENTORY//', 'Taka', 'INVENTORY', '', '', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `language`
--
DROP TABLE IF EXISTS `language`;
CREATE TABLE IF NOT EXISTS `language` (
  `phrase_id` int(11) NOT NULL AUTO_INCREMENT,
  `phrase` longtext COLLATE utf8_unicode_ci,
  `english` longtext COLLATE utf8_unicode_ci,
  `bengali` longtext COLLATE utf8_unicode_ci,
  `spanish` longtext COLLATE utf8_unicode_ci,
  `arabic` longtext COLLATE utf8_unicode_ci,
  `dutch` longtext COLLATE utf8_unicode_ci,
  `russian` longtext COLLATE utf8_unicode_ci,
  `chinese` longtext COLLATE utf8_unicode_ci,
  `turkish` longtext COLLATE utf8_unicode_ci,
  `portuguese` longtext COLLATE utf8_unicode_ci,
  `hungarian` longtext COLLATE utf8_unicode_ci,
  `french` longtext COLLATE utf8_unicode_ci,
  `greek` longtext COLLATE utf8_unicode_ci,
  `german` longtext COLLATE utf8_unicode_ci,
  `italian` longtext COLLATE utf8_unicode_ci,
  `thai` longtext COLLATE utf8_unicode_ci,
  `urdu` longtext COLLATE utf8_unicode_ci,
  `hindi` longtext COLLATE utf8_unicode_ci,
  `latin` longtext COLLATE utf8_unicode_ci,
  `indonesian` longtext COLLATE utf8_unicode_ci,
  `japanese` longtext COLLATE utf8_unicode_ci,
  `korean` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY(`phrase_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `mark`
--
DROP TABLE IF EXISTS `mark`;
CREATE TABLE IF NOT EXISTS `mark` (
  `mark_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `mark_obtained` int(11) NOT NULL DEFAULT '0',
  `mark_total` int(11) NOT NULL DEFAULT '100',
  `comment` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `year` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY(`mark_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `message`
--
DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `message_thread_code` longtext NOT NULL,
  `message` longtext NOT NULL,
  `sender` longtext NOT NULL,
  `timestamp` longtext NOT NULL,
  `read_status` int(11) NOT NULL DEFAULT '0' COMMENT '0 unread 1 read',
  PRIMARY KEY(`message_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `message_thread`
--
DROP TABLE IF EXISTS `message_thread`;
CREATE TABLE IF NOT EXISTS `message_thread` (
  `message_thread_id` int(11) NOT NULL AUTO_INCREMENT,
  `message_thread_code` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sender` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `reciever` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `last_message_timestamp` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY(`message_thread_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `noticeboard`
--
DROP TABLE IF EXISTS `noticeboard`;
CREATE TABLE IF NOT EXISTS `noticeboard` (
  `notice_id` int(11) NOT NULL AUTO_INCREMENT,
  `notice_title` longtext COLLATE utf8_unicode_ci NOT NULL,
  `notice` longtext COLLATE utf8_unicode_ci NOT NULL,
  `file_name` longtext COLLATE utf8_unicode_ci NOT NULL,
  `file_type` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `create_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY(`notice_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--
DROP TABLE IF EXISTS `notification`;
CREATE TABLE IF NOT EXISTS `notification` (
  `notice_id` int(11) NOT NULL AUTO_INCREMENT,
  `recipient` longtext NOT NULL,
  `subject` longtext NULL,
  `text` longtext NOT NULL,
  `noticedate` datetime NOT NULL,
  PRIMARY KEY(`notice_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `parent`
--
DROP TABLE IF EXISTS `parent`;
CREATE TABLE IF NOT EXISTS `parent` (
  `parent_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext COLLATE utf8_unicode_ci NOT NULL,
  `email` longtext COLLATE utf8_unicode_ci NOT NULL,
  `password` longtext COLLATE utf8_unicode_ci NOT NULL,
  `phone` longtext COLLATE utf8_unicode_ci NOT NULL,
  `address` longtext COLLATE utf8_unicode_ci NOT NULL,
  `profession` longtext COLLATE utf8_unicode_ci NOT NULL,
  `authentication_key` longtext COLLATE utf8_unicode_ci NULL,
  PRIMARY KEY(`parent_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--
DROP TABLE IF EXISTS `payment`;
CREATE TABLE IF NOT EXISTS `payment` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `expense_category_id` int(11) NOT NULL,
  `title` longtext COLLATE utf8_unicode_ci NOT NULL,
  `payment_type` longtext COLLATE utf8_unicode_ci NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `method` longtext COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `amount` longtext COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` longtext COLLATE utf8_unicode_ci NOT NULL,
  `year` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY(`payment_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `publishedmark`
--
DROP TABLE IF EXISTS `publishedmark`;
CREATE TABLE IF NOT EXISTS `publishedmark` (
  `publishedmark_id` INT(11) NOT NULL AUTO_INCREMENT,
  `student_id` INT(11) NOT NULL,
  `session_id` INT(11) NULL DEFAULT NULL,
  `course_id` INT(11) NOT NULL,
  `exam_id` INT(11) NOT NULL,
  `examtype_id` INT(11) NOT NULL,
  `mark_obtained` FLOAT NOT NULL DEFAULT '0',
  `is_absent` INT(1) NULL DEFAULT '0',
  `comment` LONGTEXT NULL COLLATE 'utf8_unicode_ci',
  `published_date` DATETIME NULL DEFAULT NULL,
  `lg` VARCHAR(50) NULL DEFAULT NULL,
  `gp` FLOAT NULL DEFAULT NULL,
  `position` INT(11) NULL DEFAULT NULL,
  `sgpa` FLOAT NULL DEFAULT NULL,
  `cgpa` FLOAT NULL DEFAULT NULL,
  `group_position` INT(11) NULL DEFAULT NULL,
  `section_position` INT(11) NULL DEFAULT NULL,
  `total_mark` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`publishedmark_id`)
);

-- --------------------------------------------------------

-- Table Result

DROP TABLE IF EXISTS `result`;
CREATE TABLE IF NOT EXISTS `result` (
	`result_id` INT(11) NOT NULL AUTO_INCREMENT,
  `student_id` INT(11) NULL DEFAULT NULL,
  `session_id` INT(11) NULL DEFAULT NULL,
  `term_id` INT(11) NULL DEFAULT NULL,
  `gpa` FLOAT NULL DEFAULT NULL,
  `cgpa` FLOAT NULL DEFAULT NULL,
  `group_position` FLOAT NULL DEFAULT NULL,
  `section_position` FLOAT NULL DEFAULT NULL,
  `total_mark` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`result_id`)
);




--
-- Table structure for table `section`
--
DROP TABLE IF EXISTS `section`;
CREATE TABLE IF NOT EXISTS `section` (
  `section_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `nick_name` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `class_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL COMMENT 'optional',
  PRIMARY KEY(`section_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `sequences`
--
DROP TABLE IF EXISTS `sequences`;
CREATE TABLE IF NOT EXISTS `sequences` (
  `componentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `uniqueCode` varchar(128) DEFAULT NULL,
  `currentValue` bigint(20) NOT NULL,
  PRIMARY KEY(`componentId`)
);

-- --------------------------------------------------------

--
-- Table structure for table `session`
--
DROP TABLE IF EXISTS `session`;
CREATE TABLE IF NOT EXISTS `session` (
  `componentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `uniqueCode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `start` date DEFAULT NULL,
  `end` date DEFAULT NULL,
  `group_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY(`componentId`)
);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--
DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `settings_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY(`settings_id`)
);

INSERT INTO `settings` (`settings_id`, `type`, `description`) VALUES
(1, 'system_name', 'CCN Polytechnic Institute,Comilla'),
(2, 'system_title', 'CCN Polytechnic Institute,Comilla'),
(3, 'address', 'Comilla, Dhaka, Bangladesh'),
(4, 'phone', '01824412272'),
(5, 'paypal_email', 'payment@school.com'),
(6, 'currency', ''),
(7, 'system_email', 'nsiddiquey@gmail.com'),
(20, 'active_sms_service', 'clickatell'),
(11, 'language', 'english'),
(12, 'text_align', 'left-to-right'),
(13, 'clickatell_user', 'Net_Soft'),
(14, 'clickatell_password', 'nSiddiquey@151'),
(15, 'clickatell_api_id', ''),
(16, 'skin_colour', 'default'),
(17, 'twilio_account_sid', ''),
(18, 'twilio_auth_token', ''),
(19, 'twilio_sender_phone_number', ''),
(21, 'running_year', '1'),
(22, 'running_term', '1'),
(23, 'default_campus', '1')
;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--
DROP TABLE IF EXISTS `student`;
CREATE TABLE IF NOT EXISTS `student` (
  `student_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_code` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `name` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `birthday` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `sex` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `religion` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `blood_group` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `address` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `paddress` longtext,
  `phone` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `email` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `password` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `parent_id` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `dormitory_id` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `transport_id` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `dormitory_room_number` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `authentication_key` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `fathername` longtext,
  `fprofession` longtext,
  `fcontactno` longtext,
  `mothername` longtext,
  `mprofession` longtext,
  `mcontactno` longtext,
  PRIMARY KEY(`student_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `studentcourseassignment`
--
DROP TABLE IF EXISTS `studentcourseassignment`;
CREATE TABLE IF NOT EXISTS `studentcourseassignment` (
  `sca_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `class_id` int(5) NOT NULL,
  `course_id` int(5) NOT NULL,
  `session_id` int(3) NOT NULL,
  PRIMARY KEY(`sca_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `student_feeconfig`
--
DROP TABLE IF EXISTS `student_feeconfig`;
CREATE TABLE IF NOT EXISTS `student_feeconfig` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `studentFeeName` varchar(255) NOT NULL,
  `studentId` int(11) NOT NULL,
  `sessionId` int(11) NOT NULL,
  `itemId` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `month` varchar(30) NOT NULL,
  `year` varchar(30) NOT NULL,
  PRIMARY KEY(`id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--
DROP TABLE IF EXISTS `subject`;
CREATE TABLE IF NOT EXISTS `subject` (
  `subject_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `class_id` int(11) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `year` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY(`subject_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--
DROP TABLE IF EXISTS `teacher`;
CREATE TABLE IF NOT EXISTS `teacher` (
  `teacher_id` int(11) NOT NULL AUTO_INCREMENT,
  `campus_id` int(3) NOT NULL,
  `name` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `birthday` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sex` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `religion` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `blood_group` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `address` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `phone` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `authentication_key` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  PRIMARY KEY(`teacher_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--
DROP TABLE IF EXISTS `transaction`;
CREATE TABLE IF NOT EXISTS `transaction` (
  `componentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `uniqueCode` varchar(128) DEFAULT NULL,
  `description` varchar(128) DEFAULT NULL,
  `tdate` datetime DEFAULT NULL,
  `month` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `type` varchar(128) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `version` int(11) DEFAULT '0',
  `createddate` datetime DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY(`componentId`)
);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_detail`
--
DROP TABLE IF EXISTS `transaction_detail`;
CREATE TABLE IF NOT EXISTS `transaction_detail` (
  `componentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `transactionId` int(11) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `itemId` int(11) DEFAULT NULL,
  `accountId` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `quantity` float DEFAULT NULL,
  `unitPrice` float DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `version` int(11) DEFAULT '0',
  `createddate` datetime DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  `month` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  PRIMARY KEY(`componentId`)
);

-- --------------------------------------------------------

--
-- Table structure for table `transport`
--
DROP TABLE IF EXISTS `transport`;
CREATE TABLE IF NOT EXISTS `transport` (
  `transport_id` int(11) NOT NULL AUTO_INCREMENT,
  `route_name` longtext COLLATE utf8_unicode_ci NOT NULL,
  `number_of_vehicle` longtext COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `route_fare` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY(`transport_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--
DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `reference_id` int(11) NOT NULL,
  `campus_id` int(3) NULL,
  `user_name` longtext NOT NULL,
  `password` longtext NOT NULL,
  `user_type` varchar(50) NOT NULL,
  PRIMARY KEY(`user_id`)
);

-- --------------------------------------------------------

CREATE OR REPLACE VIEW `vdocshare`  AS  SELECT 
  `p`.`permissionId` AS `permissionId`,`p`.`shareToId` AS `shareToId`,
  `p`.`documentId` AS `documentId`,`p`.`type` AS `type`,
  `d`.`uniqueCode` AS `uniqueCode`,`d`.`title` AS `name`,
  `d`.`description` AS `description`,
  (CASE WHEN `p`.`type` = 'CLASS' THEN `c`.`name`
   WHEN `p`.`type` = 'TEACHER' THEN `t`.`name` ELSE '' END) AS `shared` 
FROM `documentpermission` `p` 
  INNER JOIN `document` `d` ON `d`.`documentId` = `p`.`documentId` 
  LEFT JOIN `class` `c` ON (`p`.`type` = 'CLASS' AND `p`.`shareToId` = `c`.`class_id`)
  LEFT JOIN `teacher` `t` ON (`p`.`type` = 'TEACHER' AND `p`.`shareToId` = `t`.`teacher_id`) ;


-- --------------------------------------------------------

--
-- Stand-in structure for view `vexamcourse`
--
CREATE OR REPLACE VIEW `vexamcourse`  AS  SELECT 
  `e`.`examtype_id` AS `examtype_id`,
  `e`.`name` AS `name`,`ec`.`course_id` AS `course_id`,
  `ec`.`exam_id` AS `exam_id`,`ec`.`order_index` AS `order_index`,
  `ec`.`report_card` AS `report_card`,`e`.`type` AS `type` 
FROM `examcourse` `ec` 
  INNER JOIN `examtype` `e` ON `e`.`examtype_id` = `ec`.`examtype_id` ;


-- --------------------------------------------------------

--
-- Stand-in structure for view `vgradesheetheader`
--

CREATE OR REPLACE VIEW `vgradesheetheader`  AS  SELECT 
  `et`.`displayname` AS `name`,
  `ec`.`exam_id` AS `exam_id`,
  `et`.`examtype_id` AS `examtype_id`,
  `et`.`total_mark` AS `total_mark`,
  `ec`.`order_index` AS `order_index`,
  `ec`.`report_card` AS `report_card`,
  `c`.`class_id` AS `class_id` 
FROM `examcourse` `ec` 
  INNER JOIN `examtype` `et` ON `ec`.`examtype_id` = `et`.`examtype_id`
  INNER JOIN `course` `c` ON `c`.`course_id` = `ec`.`course_id` 
  WHERE `ec`.`order_index` > 0 
  GROUP BY `et`.`name`,`ec`.`exam_id`,`et`.`examtype_id`,`c`.`class_id` 
  ORDER BY `ec`.`order_index`;


-- Stand-in structure for view `vstudentcoursemark`
--
CREATE OR REPLACE VIEW `vstudentcoursemark`  AS  SELECT 
  `s`.`student_id` AS `student_id`,
  `s`.`name` AS `studentName`,`c`.`course_id` AS `course_id`,
  `c`.`tittle` AS `tittle`,`et`.`examtype_id` AS `examtype_id`,
  `et`.`name` AS `examTypeName`,`em`.`exam_id` AS `exam_id`,
  `em`.`mark_obtained` AS `mark_obtained`,
  `calcGrade`(`em`.`mark_obtained`,`em`.`examtype_id`) AS `grade`,
  `calcGradePoint`(`em`.`mark_obtained`,`em`.`examtype_id`) AS `gradePoint`,
  `highestMark`(`em`.`exam_id`,`c`.`course_id`,`et`.`examtype_id`) AS `highestMark`,
  `calcGradePoint`(`highestMark`(`em`.`exam_id`,`c`.`course_id`,`et`.`examtype_id`),
  `em`.`examtype_id`) AS `highestGradePoint` 
FROM `exammark` `em` 
  INNER JOIN `course` `c` ON `c`.`course_id` = `em`.`course_id` 
  INNER JOIN `student` `s` ON `s`.`student_id` = `em`.`student_id` 
  INNER JOIN `examtype` `et` ON `et`.`examtype_id` = `em`.`examtype_id` 
  INNER JOIN `examcourse` `ec` ON (`c`.`course_id` = `ec`.`course_id` 
    AND `ec`.`exam_id` = `em`.`exam_id` AND `ec`.`examtype_id` = `em`.`examtype_id`) 
ORDER BY `s`.`student_id`,`c`.`course_id`,`em`.`exam_id`,`ec`.`order_index` ;

-- Stand-in structure for view `vstudent_result_summery`

CREATE OR REPLACE VIEW `vstudent_result_summery`  AS  SELECT 
    `p`.`student_id` AS `student_id`,
    `c`.`tittle` AS `course_name`,
    `e`.`name` AS `exam_name`,
    `p`.`mark_obtained` AS `mark_obtained`,
    `e`.`total_mark` AS `total_mark`,
    `calcGrade`(`p`.`mark_obtained`,`e`.`examtype_id`) AS `grade`,
    `highestMark`(`p`.`exam_id`,`p`.`course_id`,`p`.`examtype_id`) AS `highest_mark` 
FROM `publishedmark` `p` 
  INNER JOIN `examtype` `e` ON `p`.`examtype_id` = `e`.`examtype_id` 
  INNER JOIN `course` `c` ON `p`.`course_id` = `c`.`course_id`  
  WHERE (`p`.`exam_id` = (SELECT MAX(`pm`.`exam_id`) 
    FROM `publishedmark` `pm` WHERE (`pm`.`student_id` = `p`.`student_id`))) ;


-- Stand-in structure for view `vtransactions`


CREATE OR REPLACE VIEW `vtransactions`  AS  SELECT 
    `t`.`componentId` AS `componentId`,
    `t`.`description` AS `description`,
    `t`.`uniqueCode` AS `uniqueCode`,
    `t`.`tdate` AS `tdate`,`t`.`type` AS `type`,
SUM(`d`.`quantity` * `d`.`unitPrice`) AS `amount` 
FROM `transaction` `t` 
  INNER JOIN `transaction_detail` `d` ON(`t`.`componentId` = `d`.`transactionId` AND `d`.`type` = -1) 
GROUP BY `t`.`componentId` ;



CREATE OR REPLACE VIEW `v_student_class`  AS  SELECT
    `s`.`student_id` AS `student_id`,
    `s`.`student_code` AS `student_code`,
    `s`.`name` AS `name`,`s`.`birthday` AS `birthday`,
    `s`.`sex` AS `sex`,`e`.`class_id` AS `class_id`,
    `e`.`section_id` AS `section_id`,`e`.`roll` AS `roll`,
    `e`.`year` AS `year`,`e`.`session_id` AS `session_id` 
FROM `student` `s` 
  INNER JOIN `enroll` `e` ON `s`.`student_id` = `e`.`student_id`;
