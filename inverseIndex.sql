CREATE DATABASE `inverseIndex` /*!40100 DEFAULT CHARACTER SET latin1 */;

-- inverseIndex.terms definition
USE `inverseIndex`;

CREATE TABLE `terms` (
  `id_term` int(11) NOT NULL AUTO_INCREMENT,
  `term` varchar(50) DEFAULT NULL,
  `num_docs` int(11) DEFAULT NULL,
  `all_frequencies` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_term`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;


-- inverseIndex.documents definition

CREATE TABLE `documents` (
  `id_document` int(11) NOT NULL AUTO_INCREMENT,
  `document_name` varchar(100) DEFAULT NULL,
  `count_words` int (11) NOT NULL,
  PRIMARY KEY (`id_document`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;


-- inverseIndex.posting definition

CREATE TABLE `posting` (
  `id_term` int(11) NOT NULL,
  `id_document` int(11) NOT NULL,
  `frecuency` int(11) DEFAULT NULL,
  `fragment_text` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_term`,`id_document`),
  KEY `posting_FK_1` (`id_document`),
  CONSTRAINT `posting_FK` FOREIGN KEY (`id_term`) REFERENCES `terms` (`id_term`),
  CONSTRAINT `posting_FK_1` FOREIGN KEY (`id_document`) REFERENCES `documents` (`id_document`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO inverseIndex.terms (id_term, term,num_docs,all_frequencies) VALUES
	 (1,'maría',1,1),
	 (2,'tiene',1,1),
	 (3,'muchos',1,1),
	 (4,'gatos',2,2),
	 (5,'los',1,1),
	 (6,'son',1,1),
	 (7,'felinos',1,1);

INSERT INTO inverseIndex.documents (id_document,document_name,count_words) VALUES
	 (1,'doc1', 10),
	 (2,'doc2', 20);
	 
INSERT INTO inverseIndex.posting (id_term,id_document,frecuency,fragment_text) VALUES
	 (1,1,1,'maria tiene muchos gatos'),
	 (2,1,1,'maria tiene muchos gatos'),
	 (3,1,1,'maria tiene muchos gatos'),
	 (4,1,1,'maria tiene muchos gatos'),
	 (4,2,1,'los gatos son felinos'),
	 (5,2,1,'los gatos son felinos'),
	 (6,2,1,'los gatos son felinos'),
	 (7,2,1,'los gatos son felinos');

