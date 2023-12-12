-- MySQL dump 10.11
--
-- Host: localhost    Database: testmanager
-- ------------------------------------------------------
-- Server version	5.0.45

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `wiki_archive`
--

DROP TABLE IF EXISTS `wiki_archive`;
CREATE TABLE `wiki_archive` (
  `ar_namespace` int(11) NOT NULL default '0',
  `ar_title` varchar(255) character set latin1 collate latin1_bin NOT NULL default '',
  `ar_text` mediumblob NOT NULL,
  `ar_comment` tinyblob NOT NULL,
  `ar_user` int(10) unsigned NOT NULL default '0',
  `ar_user_text` varchar(255) character set latin1 collate latin1_bin NOT NULL,
  `ar_timestamp` binary(14) NOT NULL default '\0\0\0\0\0\0\0\0\0\0\0\0\0\0',
  `ar_minor_edit` tinyint(4) NOT NULL default '0',
  `ar_flags` tinyblob NOT NULL,
  `ar_rev_id` int(10) unsigned default NULL,
  `ar_text_id` int(10) unsigned default NULL,
  `ar_deleted` tinyint(3) unsigned NOT NULL default '0',
  `ar_len` int(10) unsigned default NULL,
  KEY `name_title_timestamp` (`ar_namespace`,`ar_title`,`ar_timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wiki_archive`
--

LOCK TABLES `wiki_archive` WRITE;
/*!40000 ALTER TABLE `wiki_archive` DISABLE KEYS */;
/*!40000 ALTER TABLE `wiki_archive` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_categorylinks`
--

DROP TABLE IF EXISTS `wiki_categorylinks`;
CREATE TABLE `wiki_categorylinks` (
  `cl_from` int(10) unsigned NOT NULL default '0',
  `cl_to` varchar(255) character set latin1 collate latin1_bin NOT NULL default '',
  `cl_sortkey` varchar(70) character set latin1 collate latin1_bin NOT NULL default '',
  `cl_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  UNIQUE KEY `cl_from` (`cl_from`,`cl_to`),
  KEY `cl_sortkey` (`cl_to`,`cl_sortkey`),
  KEY `cl_timestamp` (`cl_to`,`cl_timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wiki_categorylinks`
--

LOCK TABLES `wiki_categorylinks` WRITE;
/*!40000 ALTER TABLE `wiki_categorylinks` DISABLE KEYS */;
/*!40000 ALTER TABLE `wiki_categorylinks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_externallinks`
--

DROP TABLE IF EXISTS `wiki_externallinks`;
CREATE TABLE `wiki_externallinks` (
  `el_from` int(10) unsigned NOT NULL default '0',
  `el_to` blob NOT NULL,
  `el_index` blob NOT NULL,
  KEY `el_from` (`el_from`,`el_to`(40)),
  KEY `el_to` (`el_to`(60),`el_from`),
  KEY `el_index` (`el_index`(60))
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wiki_externallinks`
--

LOCK TABLES `wiki_externallinks` WRITE;
/*!40000 ALTER TABLE `wiki_externallinks` DISABLE KEYS */;
/*!40000 ALTER TABLE `wiki_externallinks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_filearchive`
--

DROP TABLE IF EXISTS `wiki_filearchive`;
CREATE TABLE `wiki_filearchive` (
  `fa_id` int(11) NOT NULL auto_increment,
  `fa_name` varchar(255) character set latin1 collate latin1_bin NOT NULL default '',
  `fa_archive_name` varchar(255) character set latin1 collate latin1_bin default '',
  `fa_storage_group` varbinary(16) default NULL,
  `fa_storage_key` varbinary(64) default '',
  `fa_deleted_user` int(11) default NULL,
  `fa_deleted_timestamp` binary(14) default '\0\0\0\0\0\0\0\0\0\0\0\0\0\0',
  `fa_deleted_reason` text,
  `fa_size` int(10) unsigned default '0',
  `fa_width` int(11) default '0',
  `fa_height` int(11) default '0',
  `fa_metadata` mediumblob,
  `fa_bits` int(11) default '0',
  `fa_media_type` enum('UNKNOWN','BITMAP','DRAWING','AUDIO','VIDEO','MULTIMEDIA','OFFICE','TEXT','EXECUTABLE','ARCHIVE') default NULL,
  `fa_major_mime` enum('unknown','application','audio','image','text','video','message','model','multipart') default 'unknown',
  `fa_minor_mime` varbinary(32) default 'unknown',
  `fa_description` tinyblob,
  `fa_user` int(10) unsigned default '0',
  `fa_user_text` varchar(255) character set latin1 collate latin1_bin default NULL,
  `fa_timestamp` binary(14) default '\0\0\0\0\0\0\0\0\0\0\0\0\0\0',
  `fa_deleted` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`fa_id`),
  KEY `fa_name` (`fa_name`,`fa_timestamp`),
  KEY `fa_storage_group` (`fa_storage_group`,`fa_storage_key`),
  KEY `fa_deleted_timestamp` (`fa_deleted_timestamp`),
  KEY `fa_deleted_user` (`fa_deleted_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wiki_filearchive`
--

LOCK TABLES `wiki_filearchive` WRITE;
/*!40000 ALTER TABLE `wiki_filearchive` DISABLE KEYS */;
/*!40000 ALTER TABLE `wiki_filearchive` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_hitcounter`
--

DROP TABLE IF EXISTS `wiki_hitcounter`;
CREATE TABLE `wiki_hitcounter` (
  `hc_id` int(10) unsigned NOT NULL
) ENGINE=MEMORY DEFAULT CHARSET=latin1 MAX_ROWS=25000;

--
-- Dumping data for table `wiki_hitcounter`
--

LOCK TABLES `wiki_hitcounter` WRITE;
/*!40000 ALTER TABLE `wiki_hitcounter` DISABLE KEYS */;
/*!40000 ALTER TABLE `wiki_hitcounter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_image`
--

DROP TABLE IF EXISTS `wiki_image`;
CREATE TABLE `wiki_image` (
  `img_name` varchar(255) character set latin1 collate latin1_bin NOT NULL default '',
  `img_size` int(10) unsigned NOT NULL default '0',
  `img_width` int(11) NOT NULL default '0',
  `img_height` int(11) NOT NULL default '0',
  `img_metadata` mediumblob NOT NULL,
  `img_bits` int(11) NOT NULL default '0',
  `img_media_type` enum('UNKNOWN','BITMAP','DRAWING','AUDIO','VIDEO','MULTIMEDIA','OFFICE','TEXT','EXECUTABLE','ARCHIVE') default NULL,
  `img_major_mime` enum('unknown','application','audio','image','text','video','message','model','multipart') NOT NULL default 'unknown',
  `img_minor_mime` varbinary(32) NOT NULL default 'unknown',
  `img_description` tinyblob NOT NULL,
  `img_user` int(10) unsigned NOT NULL default '0',
  `img_user_text` varchar(255) character set latin1 collate latin1_bin NOT NULL,
  `img_timestamp` varbinary(14) NOT NULL default '',
  PRIMARY KEY  (`img_name`),
  KEY `img_size` (`img_size`),
  KEY `img_timestamp` (`img_timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wiki_image`
--

LOCK TABLES `wiki_image` WRITE;
/*!40000 ALTER TABLE `wiki_image` DISABLE KEYS */;
/*!40000 ALTER TABLE `wiki_image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_imagelinks`
--

DROP TABLE IF EXISTS `wiki_imagelinks`;
CREATE TABLE `wiki_imagelinks` (
  `il_from` int(10) unsigned NOT NULL default '0',
  `il_to` varchar(255) character set latin1 collate latin1_bin NOT NULL default '',
  UNIQUE KEY `il_from` (`il_from`,`il_to`),
  KEY `il_to` (`il_to`,`il_from`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wiki_imagelinks`
--

LOCK TABLES `wiki_imagelinks` WRITE;
/*!40000 ALTER TABLE `wiki_imagelinks` DISABLE KEYS */;
/*!40000 ALTER TABLE `wiki_imagelinks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_interwiki`
--

DROP TABLE IF EXISTS `wiki_interwiki`;
CREATE TABLE `wiki_interwiki` (
  `iw_prefix` varchar(32) NOT NULL,
  `iw_url` blob NOT NULL,
  `iw_local` tinyint(1) NOT NULL,
  `iw_trans` tinyint(4) NOT NULL default '0',
  UNIQUE KEY `iw_prefix` (`iw_prefix`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wiki_interwiki`
--

LOCK TABLES `wiki_interwiki` WRITE;
/*!40000 ALTER TABLE `wiki_interwiki` DISABLE KEYS */;
INSERT INTO `wiki_interwiki` VALUES ('abbenormal','http://www.ourpla.net/cgi-bin/pikie.cgi?$1',0,0),('acadwiki','http://xarch.tu-graz.ac.at/autocad/wiki/$1',0,0),('acronym','http://www.acronymfinder.com/af-query.asp?String=exact&Acronym=$1',0,0),('advogato','http://www.advogato.org/$1',0,0),('aiwiki','http://www.ifi.unizh.ch/ailab/aiwiki/aiw.cgi?$1',0,0),('alife','http://news.alife.org/wiki/index.php?$1',0,0),('annotation','http://bayle.stanford.edu/crit/nph-med.cgi/$1',0,0),('annotationwiki','http://www.seedwiki.com/page.cfm?wikiid=368&doc=$1',0,0),('arxiv','http://www.arxiv.org/abs/$1',0,0),('aspienetwiki','http://aspie.mela.de/Wiki/index.php?title=$1',0,0),('bemi','http://bemi.free.fr/vikio/index.php?$1',0,0),('benefitswiki','http://www.benefitslink.com/cgi-bin/wiki.cgi?$1',0,0),('brasilwiki','http://rio.ifi.unizh.ch/brasilienwiki/index.php/$1',0,0),('bridgeswiki','http://c2.com/w2/bridges/$1',0,0),('c2find','http://c2.com/cgi/wiki?FindPage&value=$1',0,0),('cache','http://www.google.com/search?q=cache:$1',0,0),('ciscavate','http://ciscavate.org/index.php/$1',0,0),('cliki','http://ww.telent.net/cliki/$1',0,0),('cmwiki','http://www.ourpla.net/cgi-bin/wiki.pl?$1',0,0),('codersbase','http://www.codersbase.com/$1',0,0),('commons','http://commons.wikimedia.org/wiki/$1',0,0),('consciousness','http://teadvus.inspiral.org/',0,0),('corpknowpedia','http://corpknowpedia.org/wiki/index.php/$1',0,0),('creationmatters','http://www.ourpla.net/cgi-bin/wiki.pl?$1',0,0),('dejanews','http://www.deja.com/=dnc/getdoc.xp?AN=$1',0,0),('demokraatia','http://wiki.demokraatia.ee/',0,0),('dictionary','http://www.dict.org/bin/Dict?Database=*&Form=Dict1&Strategy=*&Query=$1',0,0),('disinfopedia','http://www.disinfopedia.org/wiki.phtml?title=$1',0,0),('diveintoosx','http://diveintoosx.org/$1',0,0),('docbook','http://docbook.org/wiki/moin.cgi/$1',0,0),('dolphinwiki','http://www.object-arts.com/wiki/html/Dolphin/$1',0,0),('drumcorpswiki','http://www.drumcorpswiki.com/index.php/$1',0,0),('dwjwiki','http://www.suberic.net/cgi-bin/dwj/wiki.cgi?$1',0,0),('echei','http://www.ikso.net/cgi-bin/wiki.pl?$1',0,0),('ecxei','http://www.ikso.net/cgi-bin/wiki.pl?$1',0,0),('efnetceewiki','http://purl.net/wiki/c/$1',0,0),('efnetcppwiki','http://purl.net/wiki/cpp/$1',0,0),('efnetpythonwiki','http://purl.net/wiki/python/$1',0,0),('efnetxmlwiki','http://purl.net/wiki/xml/$1',0,0),('elibre','http://enciclopedia.us.es/index.php/$1',0,0),('eljwiki','http://elj.sourceforge.net/phpwiki/index.php/$1',0,0),('emacswiki','http://www.emacswiki.org/cgi-bin/wiki.pl?$1',0,0),('eokulturcentro','http://esperanto.toulouse.free.fr/wakka.php?wiki=$1',0,0),('evowiki','http://www.evowiki.org/index.php/$1',0,0),('e√Ñ‚Ä∞ei','http://www.ikso.net/cgi-bin/wiki.pl?$1',0,0),('finalempire','http://final-empire.sourceforge.net/cgi-bin/wiki.pl?$1',0,0),('firstwiki','http://firstwiki.org/index.php/$1',0,0),('foldoc','http://www.foldoc.org/foldoc/foldoc.cgi?$1',0,0),('foxwiki','http://fox.wikis.com/wc.dll?Wiki~$1',0,0),('fr.be','http://fr.wikinations.be/$1',0,0),('fr.ca','http://fr.ca.wikinations.org/$1',0,0),('fr.fr','http://fr.fr.wikinations.org/$1',0,0),('fr.org','http://fr.wikinations.org/$1',0,0),('freebsdman','http://www.FreeBSD.org/cgi/man.cgi?apropos=1&query=$1',0,0),('gamewiki','http://gamewiki.org/wiki/index.php/$1',0,0),('gej','http://www.esperanto.de/cgi-bin/aktivikio/wiki.pl?$1',0,0),('gentoo-wiki','http://gentoo-wiki.com/$1',0,0),('globalvoices','http://cyber.law.harvard.edu/dyn/globalvoices/wiki/$1',0,0),('gmailwiki','http://www.gmailwiki.com/index.php/$1',0,0),('google','http://www.google.com/search?q=$1',0,0),('googlegroups','http://groups.google.com/groups?q=$1',0,0),('gotamac','http://www.got-a-mac.org/$1',0,0),('greencheese','http://www.greencheese.org/$1',0,0),('hammondwiki','http://www.dairiki.org/HammondWiki/index.php3?$1',0,0),('haribeau','http://wiki.haribeau.de/cgi-bin/wiki.pl?$1',0,0),('herzkinderwiki','http://www.herzkinderinfo.de/Mediawiki/index.php/$1',0,0),('hewikisource','http://he.wikisource.org/wiki/$1',1,0),('hrwiki','http://www.hrwiki.org/index.php/$1',0,0),('iawiki','http://www.IAwiki.net/$1',0,0),('imdb','http://us.imdb.com/Title?$1',0,0),('infosecpedia','http://www.infosecpedia.org/pedia/index.php/$1',0,0),('jargonfile','http://sunir.org/apps/meta.pl?wiki=JargonFile&redirect=$1',0,0),('jefo','http://www.esperanto-jeunes.org/vikio/index.php?$1',0,0),('jiniwiki','http://www.cdegroot.com/cgi-bin/jini?$1',0,0),('jspwiki','http://www.ecyrd.com/JSPWiki/Wiki.jsp?page=$1',0,0),('kerimwiki','http://wiki.oxus.net/$1',0,0),('kmwiki','http://www.voght.com/cgi-bin/pywiki?$1',0,0),('knowhow','http://www2.iro.umontreal.ca/~paquetse/cgi-bin/wiki.cgi?$1',0,0),('lanifexwiki','http://opt.lanifex.com/cgi-bin/wiki.pl?$1',0,0),('lasvegaswiki','http://wiki.gmnow.com/index.php/$1',0,0),('linuxwiki','http://www.linuxwiki.de/$1',0,0),('lojban','http://www.lojban.org/tiki/tiki-index.php?page=$1',0,0),('lqwiki','http://wiki.linuxquestions.org/wiki/$1',0,0),('lugkr','http://lug-kr.sourceforge.net/cgi-bin/lugwiki.pl?$1',0,0),('lutherwiki','http://www.lutheranarchives.com/mw/index.php/$1',0,0),('mathsongswiki','http://SeedWiki.com/page.cfm?wikiid=237&doc=$1',0,0),('mbtest','http://www.usemod.com/cgi-bin/mbtest.pl?$1',0,0),('meatball','http://www.usemod.com/cgi-bin/mb.pl?$1',0,0),('mediawikiwiki','http://www.mediawiki.org/wiki/$1',0,0),('mediazilla','http://bugzilla.wikipedia.org/$1',1,0),('memoryalpha','http://www.memory-alpha.org/en/index.php/$1',0,0),('metaweb','http://www.metaweb.com/wiki/wiki.phtml?title=$1',0,0),('metawiki','http://sunir.org/apps/meta.pl?$1',0,0),('metawikipedia','http://meta.wikimedia.org/wiki/$1',0,0),('moinmoin','http://purl.net/wiki/moin/$1',0,0),('mozillawiki','http://wiki.mozilla.org/index.php/$1',0,0),('muweb','http://www.dunstable.com/scripts/MuWebWeb?$1',0,0),('netvillage','http://www.netbros.com/?$1',0,0),('oeis','http://www.research.att.com/cgi-bin/access.cgi/as/njas/sequences/eisA.cgi?Anum=$1',0,0),('openfacts','http://openfacts.berlios.de/index.phtml?title=$1',0,0),('openwiki','http://openwiki.com/?$1',0,0),('opera7wiki','http://nontroppo.org/wiki/$1',0,0),('orgpatterns','http://www.bell-labs.com/cgi-user/OrgPatterns/OrgPatterns?$1',0,0),('osi reference model','http://wiki.tigma.ee/',0,0),('pangalacticorg','http://www.pangalactic.org/Wiki/$1',0,0),('patwiki','http://gauss.ffii.org/$1',0,0),('personaltelco','http://www.personaltelco.net/index.cgi/$1',0,0),('phpwiki','http://phpwiki.sourceforge.net/phpwiki/index.php?$1',0,0),('pikie','http://pikie.darktech.org/cgi/pikie?$1',0,0),('pmeg','http://www.bertilow.com/pmeg/$1.php',0,0),('ppr','http://c2.com/cgi/wiki?$1',0,0),('purlnet','http://purl.oclc.org/NET/$1',0,0),('pythoninfo','http://www.python.org/cgi-bin/moinmoin/$1',0,0),('pythonwiki','http://www.pythonwiki.de/$1',0,0),('pywiki','http://www.voght.com/cgi-bin/pywiki?$1',0,0),('raec','http://www.raec.clacso.edu.ar:8080/raec/Members/raecpedia/$1',0,0),('revo','http://purl.org/NET/voko/revo/art/$1.html',0,0),('rfc','http://www.rfc-editor.org/rfc/rfc$1.txt',0,0),('s23wiki','http://is-root.de/wiki/index.php/$1',0,0),('scoutpedia','http://www.scoutpedia.info/index.php/$1',0,0),('seapig','http://www.seapig.org/$1',0,0),('seattlewiki','http://seattlewiki.org/wiki/$1',0,0),('seattlewireless','http://seattlewireless.net/?$1',0,0),('seeds','http://www.IslandSeeds.org/wiki/$1',0,0),('senseislibrary','http://senseis.xmp.net/?$1',0,0),('shakti','http://cgi.algonet.se/htbin/cgiwrap/pgd/ShaktiWiki/$1',0,0),('slashdot','http://slashdot.org/article.pl?sid=$1',0,0),('smikipedia','http://www.smikipedia.org/$1',0,0),('sockwiki','http://wiki.socklabs.com/$1',0,0),('sourceforge','http://sourceforge.net/$1',0,0),('squeak','http://minnow.cc.gatech.edu/squeak/$1',0,0),('strikiwiki','http://ch.twi.tudelft.nl/~mostert/striki/teststriki.pl?$1',0,0),('susning','http://www.susning.nu/$1',0,0),('svgwiki','http://www.protocol7.com/svg-wiki/default.asp?$1',0,0),('tavi','http://tavi.sourceforge.net/$1',0,0),('tejo','http://www.tejo.org/vikio/$1',0,0),('terrorwiki','http://www.liberalsagainstterrorism.com/wiki/index.php/$1',0,0),('theopedia','http://www.theopedia.com/$1',0,0),('tmbw','http://www.tmbw.net/wiki/index.php/$1',0,0),('tmnet','http://www.technomanifestos.net/?$1',0,0),('tmwiki','http://www.EasyTopicMaps.com/?page=$1',0,0),('turismo','http://www.tejo.org/turismo/$1',0,0),('twiki','http://twiki.org/cgi-bin/view/$1',0,0),('twistedwiki','http://purl.net/wiki/twisted/$1',0,0),('uea','http://www.tejo.org/uea/$1',0,0),('unreal','http://wiki.beyondunreal.com/wiki/$1',0,0),('ursine','http://wiki.ursine.ca/$1',0,0),('usej','http://www.tejo.org/usej/$1',0,0),('usemod','http://www.usemod.com/cgi-bin/wiki.pl?$1',0,0),('visualworks','http://wiki.cs.uiuc.edu/VisualWorks/$1',0,0),('warpedview','http://www.warpedview.com/index.php/$1',0,0),('webdevwikinl','http://www.promo-it.nl/WebDevWiki/index.php?page=$1',0,0),('webisodes','http://www.webisodes.org/$1',0,0),('webseitzwiki','http://webseitz.fluxent.com/wiki/$1',0,0),('why','http://clublet.com/c/c/why?$1',0,0),('wiki','http://c2.com/cgi/wiki?$1',0,0),('wikia','http://www.wikia.com/wiki/$1',0,0),('wikibooks','http://en.wikibooks.org/wiki/$1',1,0),('wikicities','http://www.wikicities.com/index.php/$1',0,0),('wikif1','http://www.wikif1.org/$1',0,0),('wikihow','http://www.wikihow.com/$1',0,0),('wikimedia','http://wikimediafoundation.org/wiki/$1',0,0),('wikinews','http://en.wikinews.org/wiki/$1',0,0),('wikinfo','http://www.wikinfo.org/wiki.php?title=$1',0,0),('wikipedia','http://en.wikipedia.org/wiki/$1',1,0),('wikiquote','http://en.wikiquote.org/wiki/$1',1,0),('wikisource','http://sources.wikipedia.org/wiki/$1',1,0),('wikispecies','http://species.wikipedia.org/wiki/$1',1,0),('wikitravel','http://wikitravel.org/en/$1',0,0),('wikiworld','http://WikiWorld.com/wiki/index.php/$1',0,0),('wikt','http://en.wiktionary.org/wiki/$1',1,0),('wiktionary','http://en.wiktionary.org/wiki/$1',1,0),('wlug','http://www.wlug.org.nz/$1',0,0),('wlwiki','http://winslowslair.supremepixels.net/wiki/index.php/$1',0,0),('ypsieyeball','http://sknkwrks.dyndns.org:1957/writewiki/wiki.pl?$1',0,0),('zwiki','http://www.zwiki.org/$1',0,0),('zzz wiki','http://wiki.zzz.ee/',0,0);
/*!40000 ALTER TABLE `wiki_interwiki` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_ipblocks`
--

DROP TABLE IF EXISTS `wiki_ipblocks`;
CREATE TABLE `wiki_ipblocks` (
  `ipb_id` int(11) NOT NULL auto_increment,
  `ipb_address` tinyblob NOT NULL,
  `ipb_user` int(10) unsigned NOT NULL default '0',
  `ipb_by` int(10) unsigned NOT NULL default '0',
  `ipb_reason` tinyblob NOT NULL,
  `ipb_timestamp` binary(14) NOT NULL default '\0\0\0\0\0\0\0\0\0\0\0\0\0\0',
  `ipb_auto` tinyint(1) NOT NULL default '0',
  `ipb_anon_only` tinyint(1) NOT NULL default '0',
  `ipb_create_account` tinyint(1) NOT NULL default '1',
  `ipb_enable_autoblock` tinyint(1) NOT NULL default '1',
  `ipb_expiry` varbinary(14) NOT NULL default '',
  `ipb_range_start` tinyblob NOT NULL,
  `ipb_range_end` tinyblob NOT NULL,
  `ipb_deleted` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`ipb_id`),
  UNIQUE KEY `ipb_address` (`ipb_address`(255),`ipb_user`,`ipb_auto`,`ipb_anon_only`),
  KEY `ipb_user` (`ipb_user`),
  KEY `ipb_range` (`ipb_range_start`(8),`ipb_range_end`(8)),
  KEY `ipb_timestamp` (`ipb_timestamp`),
  KEY `ipb_expiry` (`ipb_expiry`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wiki_ipblocks`
--

LOCK TABLES `wiki_ipblocks` WRITE;
/*!40000 ALTER TABLE `wiki_ipblocks` DISABLE KEYS */;
/*!40000 ALTER TABLE `wiki_ipblocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_job`
--

DROP TABLE IF EXISTS `wiki_job`;
CREATE TABLE `wiki_job` (
  `job_id` int(10) unsigned NOT NULL auto_increment,
  `job_cmd` varbinary(60) NOT NULL default '',
  `job_namespace` int(11) NOT NULL,
  `job_title` varchar(255) character set latin1 collate latin1_bin NOT NULL,
  `job_params` blob NOT NULL,
  PRIMARY KEY  (`job_id`),
  KEY `job_cmd` (`job_cmd`,`job_namespace`,`job_title`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wiki_job`
--

LOCK TABLES `wiki_job` WRITE;
/*!40000 ALTER TABLE `wiki_job` DISABLE KEYS */;
/*!40000 ALTER TABLE `wiki_job` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_langlinks`
--

DROP TABLE IF EXISTS `wiki_langlinks`;
CREATE TABLE `wiki_langlinks` (
  `ll_from` int(10) unsigned NOT NULL default '0',
  `ll_lang` varbinary(20) NOT NULL default '',
  `ll_title` varchar(255) character set latin1 collate latin1_bin NOT NULL default '',
  UNIQUE KEY `ll_from` (`ll_from`,`ll_lang`),
  KEY `ll_lang` (`ll_lang`,`ll_title`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wiki_langlinks`
--

LOCK TABLES `wiki_langlinks` WRITE;
/*!40000 ALTER TABLE `wiki_langlinks` DISABLE KEYS */;
/*!40000 ALTER TABLE `wiki_langlinks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_logging`
--

DROP TABLE IF EXISTS `wiki_logging`;
CREATE TABLE `wiki_logging` (
  `log_type` varbinary(10) NOT NULL default '',
  `log_action` varbinary(10) NOT NULL default '',
  `log_timestamp` binary(14) NOT NULL default '19700101000000',
  `log_user` int(10) unsigned NOT NULL default '0',
  `log_namespace` int(11) NOT NULL default '0',
  `log_title` varchar(255) character set latin1 collate latin1_bin NOT NULL default '',
  `log_comment` varchar(255) NOT NULL default '',
  `log_params` blob NOT NULL,
  `log_id` int(10) unsigned NOT NULL auto_increment,
  `log_deleted` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`log_id`),
  KEY `type_time` (`log_type`,`log_timestamp`),
  KEY `user_time` (`log_user`,`log_timestamp`),
  KEY `page_time` (`log_namespace`,`log_title`,`log_timestamp`),
  KEY `times` (`log_timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wiki_logging`
--

LOCK TABLES `wiki_logging` WRITE;
/*!40000 ALTER TABLE `wiki_logging` DISABLE KEYS */;
/*!40000 ALTER TABLE `wiki_logging` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_math`
--

DROP TABLE IF EXISTS `wiki_math`;
CREATE TABLE `wiki_math` (
  `math_inputhash` varbinary(16) NOT NULL,
  `math_outputhash` varbinary(16) NOT NULL,
  `math_html_conservativeness` tinyint(4) NOT NULL,
  `math_html` text,
  `math_mathml` text,
  UNIQUE KEY `math_inputhash` (`math_inputhash`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wiki_math`
--

LOCK TABLES `wiki_math` WRITE;
/*!40000 ALTER TABLE `wiki_math` DISABLE KEYS */;
/*!40000 ALTER TABLE `wiki_math` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_objectcache`
--

DROP TABLE IF EXISTS `wiki_objectcache`;
CREATE TABLE `wiki_objectcache` (
  `keyname` varbinary(255) NOT NULL default '',
  `value` mediumblob,
  `exptime` datetime default NULL,
  UNIQUE KEY `keyname` (`keyname`),
  KEY `exptime` (`exptime`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wiki_objectcache`
--

LOCK TABLES `wiki_objectcache` WRITE;
/*!40000 ALTER TABLE `wiki_objectcache` DISABLE KEYS */;
INSERT INTO `wiki_objectcache` VALUES ('testmanager-wiki_:messages','K¥2¥™.∂2∑R\ns\r\nˆÙ˜S≤Œ¥2¥Æ\0','2009-10-04 01:18:06'),('testmanager-wiki_:pcache:idhash:1-0!1!0!!en!2','•U[O€0~ÔØpÛ∞=ÂVXnöiBê(0—m”Tπ…ib·8QÏ, ƒﬂ±Iª∂l\Z©Â\\>üœ%ó4PÁä’\nÍÀFWçvh8¢˜ä~†N1É[Ìå∆åˆ©Uq¥‡˛ƒSH9˚Œo8…ô\"\0ITì$†‘≤‚ép©4R/Ú1~1≠˘P≈G•Tç–DÁ@\"FÚ\Zñ\'◊∫¢æ_Äf^ã∏Ö9¿+ÎÃ7O˛)àäb¢©ïC¡îö8»j…—Ü\'—\\x)T\rb‚»rY\nQ∂N¸Ô·Ω\"\'\rO!ÚYLñeçj∑`öóí‡ßQ\\fñæ$™\\Íñ’‡YÅ=î$YÅ<N@kú„M‘\ZR\'6xQ>à#U1π÷êr≠ 1ÿN¸c}æ•&Yı#o.S∏ı™º˙¯®s ∏ú_°ˇ+™1≥Éfü;»I∏æñctëŒLIGé¨…ôLCg‰z1ŸbY¥n,\\ÇÔ&ì.%ÚQ\\/jD	?©m€∂û≠ÖëÛ¥Kû5µΩ„πzƒf°_ÉªSı≠0≤\n#Ç+{\'XXÙbQü?}yª≤C˜œÙ°˜?∆≈Œ∂ño‰ô÷ˆ◊gªL ≤ë	<sƒ^â˝O9Ë\0¶Ä”`;±µzΩ®Ô∫‰ö˝¬∆„íTvuëÑ%vuNn‡élLèkü” FPû‚¬ iË˝∞Ù˚ ˚¬dä \nÃ¡—!É 8É`/√É0 Æ˜Ïƒ\rXú3ô5àzŒÂçr∆åÙ˛}!˙éòÜ¨¨9l:F∆ÅÀáU]ät 2û8„\rb`≥ê÷_ù±}º±`ÿ7®ïYcªòCoËÖŒ\nbfä≤Z’h0CCvá§çÖ¢Ht√n(û(j3÷à=Ójø∆Ÿ7ÔÖ}tΩh≈é9\r\rÌ}√ƒv {œ1C”eC˙∆vµ@´˚;ùMœÌl‹ı!⁄Øõ≈ÆŸ6≈¥◊›vﬂ¨˙Eyb^èı›ñıWÏôÜ‚I—VΩ˘óÓc#<¸','2009-10-04 01:18:10');
/*!40000 ALTER TABLE `wiki_objectcache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_oldimage`
--

DROP TABLE IF EXISTS `wiki_oldimage`;
CREATE TABLE `wiki_oldimage` (
  `oi_name` varchar(255) character set latin1 collate latin1_bin NOT NULL default '',
  `oi_archive_name` varchar(255) character set latin1 collate latin1_bin NOT NULL default '',
  `oi_size` int(10) unsigned NOT NULL default '0',
  `oi_width` int(11) NOT NULL default '0',
  `oi_height` int(11) NOT NULL default '0',
  `oi_bits` int(11) NOT NULL default '0',
  `oi_description` tinyblob NOT NULL,
  `oi_user` int(10) unsigned NOT NULL default '0',
  `oi_user_text` varchar(255) character set latin1 collate latin1_bin NOT NULL,
  `oi_timestamp` binary(14) NOT NULL default '\0\0\0\0\0\0\0\0\0\0\0\0\0\0',
  KEY `oi_name` (`oi_name`(10))
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wiki_oldimage`
--

LOCK TABLES `wiki_oldimage` WRITE;
/*!40000 ALTER TABLE `wiki_oldimage` DISABLE KEYS */;
/*!40000 ALTER TABLE `wiki_oldimage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_page`
--

DROP TABLE IF EXISTS `wiki_page`;
CREATE TABLE `wiki_page` (
  `page_id` int(10) unsigned NOT NULL auto_increment,
  `page_namespace` int(11) NOT NULL,
  `page_title` varchar(255) character set latin1 collate latin1_bin NOT NULL,
  `page_restrictions` tinyblob NOT NULL,
  `page_counter` bigint(20) unsigned NOT NULL default '0',
  `page_is_redirect` tinyint(3) unsigned NOT NULL default '0',
  `page_is_new` tinyint(3) unsigned NOT NULL default '0',
  `page_random` double unsigned NOT NULL,
  `page_touched` binary(14) NOT NULL default '\0\0\0\0\0\0\0\0\0\0\0\0\0\0',
  `page_latest` int(10) unsigned NOT NULL,
  `page_len` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`page_id`),
  UNIQUE KEY `name_title` (`page_namespace`,`page_title`),
  KEY `page_random` (`page_random`),
  KEY `page_len` (`page_len`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wiki_page`
--

LOCK TABLES `wiki_page` WRITE;
/*!40000 ALTER TABLE `wiki_page` DISABLE KEYS */;
INSERT INTO `wiki_page` VALUES (1,0,'Main_Page','',1,0,0,0.798448700218,'20091003011641',1,444);
/*!40000 ALTER TABLE `wiki_page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_page_restrictions`
--

DROP TABLE IF EXISTS `wiki_page_restrictions`;
CREATE TABLE `wiki_page_restrictions` (
  `pr_page` int(11) NOT NULL,
  `pr_type` varbinary(60) NOT NULL,
  `pr_level` varbinary(60) NOT NULL,
  `pr_cascade` tinyint(4) NOT NULL,
  `pr_user` int(11) default NULL,
  `pr_expiry` varbinary(14) default NULL,
  `pr_id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`pr_page`,`pr_type`),
  UNIQUE KEY `pr_id` (`pr_id`),
  KEY `pr_page` (`pr_page`),
  KEY `pr_typelevel` (`pr_type`,`pr_level`),
  KEY `pr_level` (`pr_level`),
  KEY `pr_cascade` (`pr_cascade`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wiki_page_restrictions`
--

LOCK TABLES `wiki_page_restrictions` WRITE;
/*!40000 ALTER TABLE `wiki_page_restrictions` DISABLE KEYS */;
/*!40000 ALTER TABLE `wiki_page_restrictions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_pagelinks`
--

DROP TABLE IF EXISTS `wiki_pagelinks`;
CREATE TABLE `wiki_pagelinks` (
  `pl_from` int(10) unsigned NOT NULL default '0',
  `pl_namespace` int(11) NOT NULL default '0',
  `pl_title` varchar(255) character set latin1 collate latin1_bin NOT NULL default '',
  UNIQUE KEY `pl_from` (`pl_from`,`pl_namespace`,`pl_title`),
  KEY `pl_namespace` (`pl_namespace`,`pl_title`,`pl_from`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wiki_pagelinks`
--

LOCK TABLES `wiki_pagelinks` WRITE;
/*!40000 ALTER TABLE `wiki_pagelinks` DISABLE KEYS */;
/*!40000 ALTER TABLE `wiki_pagelinks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_querycache`
--

DROP TABLE IF EXISTS `wiki_querycache`;
CREATE TABLE `wiki_querycache` (
  `qc_type` varbinary(32) NOT NULL,
  `qc_value` int(10) unsigned NOT NULL default '0',
  `qc_namespace` int(11) NOT NULL default '0',
  `qc_title` char(255) character set latin1 collate latin1_bin NOT NULL default '',
  KEY `qc_type` (`qc_type`,`qc_value`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wiki_querycache`
--

LOCK TABLES `wiki_querycache` WRITE;
/*!40000 ALTER TABLE `wiki_querycache` DISABLE KEYS */;
/*!40000 ALTER TABLE `wiki_querycache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_querycache_info`
--

DROP TABLE IF EXISTS `wiki_querycache_info`;
CREATE TABLE `wiki_querycache_info` (
  `qci_type` varbinary(32) NOT NULL default '',
  `qci_timestamp` binary(14) NOT NULL default '19700101000000',
  UNIQUE KEY `qci_type` (`qci_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wiki_querycache_info`
--

LOCK TABLES `wiki_querycache_info` WRITE;
/*!40000 ALTER TABLE `wiki_querycache_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `wiki_querycache_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_querycachetwo`
--

DROP TABLE IF EXISTS `wiki_querycachetwo`;
CREATE TABLE `wiki_querycachetwo` (
  `qcc_type` varbinary(32) NOT NULL,
  `qcc_value` int(10) unsigned NOT NULL default '0',
  `qcc_namespace` int(11) NOT NULL default '0',
  `qcc_title` char(255) character set latin1 collate latin1_bin NOT NULL default '',
  `qcc_namespacetwo` int(11) NOT NULL default '0',
  `qcc_titletwo` char(255) character set latin1 collate latin1_bin NOT NULL default '',
  KEY `qcc_type` (`qcc_type`,`qcc_value`),
  KEY `qcc_title` (`qcc_type`,`qcc_namespace`,`qcc_title`),
  KEY `qcc_titletwo` (`qcc_type`,`qcc_namespacetwo`,`qcc_titletwo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wiki_querycachetwo`
--

LOCK TABLES `wiki_querycachetwo` WRITE;
/*!40000 ALTER TABLE `wiki_querycachetwo` DISABLE KEYS */;
/*!40000 ALTER TABLE `wiki_querycachetwo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_recentchanges`
--

DROP TABLE IF EXISTS `wiki_recentchanges`;
CREATE TABLE `wiki_recentchanges` (
  `rc_id` int(11) NOT NULL auto_increment,
  `rc_timestamp` varbinary(14) NOT NULL default '',
  `rc_cur_time` varbinary(14) NOT NULL default '',
  `rc_user` int(10) unsigned NOT NULL default '0',
  `rc_user_text` varchar(255) character set latin1 collate latin1_bin NOT NULL,
  `rc_namespace` int(11) NOT NULL default '0',
  `rc_title` varchar(255) character set latin1 collate latin1_bin NOT NULL default '',
  `rc_comment` varchar(255) character set latin1 collate latin1_bin NOT NULL default '',
  `rc_minor` tinyint(3) unsigned NOT NULL default '0',
  `rc_bot` tinyint(3) unsigned NOT NULL default '0',
  `rc_new` tinyint(3) unsigned NOT NULL default '0',
  `rc_cur_id` int(10) unsigned NOT NULL default '0',
  `rc_this_oldid` int(10) unsigned NOT NULL default '0',
  `rc_last_oldid` int(10) unsigned NOT NULL default '0',
  `rc_type` tinyint(3) unsigned NOT NULL default '0',
  `rc_moved_to_ns` tinyint(3) unsigned NOT NULL default '0',
  `rc_moved_to_title` varchar(255) character set latin1 collate latin1_bin NOT NULL default '',
  `rc_patrolled` tinyint(3) unsigned NOT NULL default '0',
  `rc_ip` varbinary(40) NOT NULL default '',
  `rc_old_len` int(11) default NULL,
  `rc_new_len` int(11) default NULL,
  `rc_deleted` tinyint(3) unsigned NOT NULL default '0',
  `rc_logid` int(10) unsigned NOT NULL default '0',
  `rc_log_type` varbinary(255) default NULL,
  `rc_log_action` varbinary(255) default NULL,
  `rc_params` blob NOT NULL,
  PRIMARY KEY  (`rc_id`),
  KEY `rc_timestamp` (`rc_timestamp`),
  KEY `rc_namespace_title` (`rc_namespace`,`rc_title`),
  KEY `rc_cur_id` (`rc_cur_id`),
  KEY `new_name_timestamp` (`rc_new`,`rc_namespace`,`rc_timestamp`),
  KEY `rc_ip` (`rc_ip`),
  KEY `rc_ns_usertext` (`rc_namespace`,`rc_user_text`),
  KEY `rc_user_text` (`rc_user_text`,`rc_timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wiki_recentchanges`
--

LOCK TABLES `wiki_recentchanges` WRITE;
/*!40000 ALTER TABLE `wiki_recentchanges` DISABLE KEYS */;
/*!40000 ALTER TABLE `wiki_recentchanges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_redirect`
--

DROP TABLE IF EXISTS `wiki_redirect`;
CREATE TABLE `wiki_redirect` (
  `rd_from` int(10) unsigned NOT NULL default '0',
  `rd_namespace` int(11) NOT NULL default '0',
  `rd_title` varchar(255) character set latin1 collate latin1_bin NOT NULL default '',
  PRIMARY KEY  (`rd_from`),
  KEY `rd_ns_title` (`rd_namespace`,`rd_title`,`rd_from`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wiki_redirect`
--

LOCK TABLES `wiki_redirect` WRITE;
/*!40000 ALTER TABLE `wiki_redirect` DISABLE KEYS */;
/*!40000 ALTER TABLE `wiki_redirect` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_revision`
--

DROP TABLE IF EXISTS `wiki_revision`;
CREATE TABLE `wiki_revision` (
  `rev_id` int(10) unsigned NOT NULL auto_increment,
  `rev_page` int(10) unsigned NOT NULL,
  `rev_text_id` int(10) unsigned NOT NULL,
  `rev_comment` tinyblob NOT NULL,
  `rev_user` int(10) unsigned NOT NULL default '0',
  `rev_user_text` varchar(255) character set latin1 collate latin1_bin NOT NULL default '',
  `rev_timestamp` binary(14) NOT NULL default '\0\0\0\0\0\0\0\0\0\0\0\0\0\0',
  `rev_minor_edit` tinyint(3) unsigned NOT NULL default '0',
  `rev_deleted` tinyint(3) unsigned NOT NULL default '0',
  `rev_len` int(10) unsigned default NULL,
  `rev_parent_id` int(10) unsigned default NULL,
  PRIMARY KEY  (`rev_page`,`rev_id`),
  UNIQUE KEY `rev_id` (`rev_id`),
  KEY `rev_timestamp` (`rev_timestamp`),
  KEY `page_timestamp` (`rev_page`,`rev_timestamp`),
  KEY `user_timestamp` (`rev_user`,`rev_timestamp`),
  KEY `usertext_timestamp` (`rev_user_text`,`rev_timestamp`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 MAX_ROWS=10000000 AVG_ROW_LENGTH=1024;

--
-- Dumping data for table `wiki_revision`
--

LOCK TABLES `wiki_revision` WRITE;
/*!40000 ALTER TABLE `wiki_revision` DISABLE KEYS */;
INSERT INTO `wiki_revision` VALUES (1,1,1,'',0,'MediaWiki default','20091003011641',0,0,444,NULL);
/*!40000 ALTER TABLE `wiki_revision` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_searchindex`
--

DROP TABLE IF EXISTS `wiki_searchindex`;
CREATE TABLE `wiki_searchindex` (
  `si_page` int(10) unsigned NOT NULL,
  `si_title` varchar(255) NOT NULL default '',
  `si_text` mediumtext NOT NULL,
  UNIQUE KEY `si_page` (`si_page`),
  FULLTEXT KEY `si_title` (`si_title`),
  FULLTEXT KEY `si_text` (`si_text`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wiki_searchindex`
--

LOCK TABLES `wiki_searchindex` WRITE;
/*!40000 ALTER TABLE `wiki_searchindex` DISABLE KEYS */;
/*!40000 ALTER TABLE `wiki_searchindex` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_site_stats`
--

DROP TABLE IF EXISTS `wiki_site_stats`;
CREATE TABLE `wiki_site_stats` (
  `ss_row_id` int(10) unsigned NOT NULL,
  `ss_total_views` bigint(20) unsigned default '0',
  `ss_total_edits` bigint(20) unsigned default '0',
  `ss_good_articles` bigint(20) unsigned default '0',
  `ss_total_pages` bigint(20) default '-1',
  `ss_users` bigint(20) default '-1',
  `ss_admins` int(11) default '-1',
  `ss_images` int(11) default '0',
  UNIQUE KEY `ss_row_id` (`ss_row_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wiki_site_stats`
--

LOCK TABLES `wiki_site_stats` WRITE;
/*!40000 ALTER TABLE `wiki_site_stats` DISABLE KEYS */;
INSERT INTO `wiki_site_stats` VALUES (1,1,0,0,-1,-1,-1,0);
/*!40000 ALTER TABLE `wiki_site_stats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_templatelinks`
--

DROP TABLE IF EXISTS `wiki_templatelinks`;
CREATE TABLE `wiki_templatelinks` (
  `tl_from` int(10) unsigned NOT NULL default '0',
  `tl_namespace` int(11) NOT NULL default '0',
  `tl_title` varchar(255) character set latin1 collate latin1_bin NOT NULL default '',
  UNIQUE KEY `tl_from` (`tl_from`,`tl_namespace`,`tl_title`),
  KEY `tl_namespace` (`tl_namespace`,`tl_title`,`tl_from`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wiki_templatelinks`
--

LOCK TABLES `wiki_templatelinks` WRITE;
/*!40000 ALTER TABLE `wiki_templatelinks` DISABLE KEYS */;
/*!40000 ALTER TABLE `wiki_templatelinks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_text`
--

DROP TABLE IF EXISTS `wiki_text`;
CREATE TABLE `wiki_text` (
  `old_id` int(10) unsigned NOT NULL auto_increment,
  `old_text` mediumblob NOT NULL,
  `old_flags` tinyblob NOT NULL,
  PRIMARY KEY  (`old_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 MAX_ROWS=10000000 AVG_ROW_LENGTH=10240;

--
-- Dumping data for table `wiki_text`
--

LOCK TABLES `wiki_text` WRITE;
/*!40000 ALTER TABLE `wiki_text` DISABLE KEYS */;
INSERT INTO `wiki_text` VALUES (1,'<big>\'\'\'MediaWiki has been successfully installed.\'\'\'</big>\n\nConsult the [http://meta.wikimedia.org/wiki/Help:Contents User\'s Guide] for information on using the wiki software.\n\n== Getting started ==\n\n* [http://www.mediawiki.org/wiki/Help:Configuration_settings Configuration settings list]\n* [http://www.mediawiki.org/wiki/Help:FAQ MediaWiki FAQ]\n* [http://mail.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]','utf-8');
/*!40000 ALTER TABLE `wiki_text` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_trackbacks`
--

DROP TABLE IF EXISTS `wiki_trackbacks`;
CREATE TABLE `wiki_trackbacks` (
  `tb_id` int(11) NOT NULL auto_increment,
  `tb_page` int(11) default NULL,
  `tb_title` varchar(255) NOT NULL,
  `tb_url` blob NOT NULL,
  `tb_ex` text,
  `tb_name` varchar(255) default NULL,
  PRIMARY KEY  (`tb_id`),
  KEY `tb_page` (`tb_page`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wiki_trackbacks`
--

LOCK TABLES `wiki_trackbacks` WRITE;
/*!40000 ALTER TABLE `wiki_trackbacks` DISABLE KEYS */;
/*!40000 ALTER TABLE `wiki_trackbacks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_transcache`
--

DROP TABLE IF EXISTS `wiki_transcache`;
CREATE TABLE `wiki_transcache` (
  `tc_url` varbinary(255) NOT NULL,
  `tc_contents` text,
  `tc_time` int(11) NOT NULL,
  UNIQUE KEY `tc_url_idx` (`tc_url`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wiki_transcache`
--

LOCK TABLES `wiki_transcache` WRITE;
/*!40000 ALTER TABLE `wiki_transcache` DISABLE KEYS */;
/*!40000 ALTER TABLE `wiki_transcache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_user`
--

DROP TABLE IF EXISTS `wiki_user`;
CREATE TABLE `wiki_user` (
  `user_id` int(10) unsigned NOT NULL auto_increment,
  `user_name` varchar(255) character set latin1 collate latin1_bin NOT NULL default '',
  `user_real_name` varchar(255) character set latin1 collate latin1_bin NOT NULL default '',
  `user_password` tinyblob NOT NULL,
  `user_newpassword` tinyblob NOT NULL,
  `user_newpass_time` binary(14) default NULL,
  `user_email` tinytext NOT NULL,
  `user_options` blob NOT NULL,
  `user_touched` binary(14) NOT NULL default '\0\0\0\0\0\0\0\0\0\0\0\0\0\0',
  `user_token` binary(32) NOT NULL default '\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0',
  `user_email_authenticated` binary(14) default NULL,
  `user_email_token` binary(32) default NULL,
  `user_email_token_expires` binary(14) default NULL,
  `user_registration` binary(14) default NULL,
  `user_editcount` int(11) default NULL,
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `user_name` (`user_name`),
  KEY `user_email_token` (`user_email_token`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wiki_user`
--

LOCK TABLES `wiki_user` WRITE;
/*!40000 ALTER TABLE `wiki_user` DISABLE KEYS */;
INSERT INTO `wiki_user` VALUES (1,'Admin','','47bf2edac8048889e234867fc55db95d','',NULL,'','quickbar=1\nunderline=2\ncols=80\nrows=25\nsearchlimit=20\ncontextlines=5\ncontextchars=50\nskin=\nmath=1\nrcdays=7\nrclimit=50\nwllimit=250\nhighlightbroken=1\nstubthreshold=0\npreviewontop=1\neditsection=1\neditsectiononrightclick=0\nshowtoc=1\nshowtoolbar=1\ndate=default\nimagesize=2\nthumbsize=2\nrememberpassword=0\nenotifwatchlistpages=0\nenotifusertalkpages=1\nenotifminoredits=0\nenotifrevealaddr=0\nshownumberswatching=1\nfancysig=0\nexternaleditor=0\nexternaldiff=0\nshowjumplinks=1\nnumberheadings=0\nuselivepreview=0\nwatchlistdays=3\nvariant=en\nlanguage=en\nsearchNs0=1','20091003011645','f1b657bf1e8fc8cd83dbc45175ddf044',NULL,NULL,NULL,'20091003011640',0);
/*!40000 ALTER TABLE `wiki_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_user_groups`
--

DROP TABLE IF EXISTS `wiki_user_groups`;
CREATE TABLE `wiki_user_groups` (
  `ug_user` int(10) unsigned NOT NULL default '0',
  `ug_group` varbinary(16) NOT NULL default '',
  PRIMARY KEY  (`ug_user`,`ug_group`),
  KEY `ug_group` (`ug_group`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wiki_user_groups`
--

LOCK TABLES `wiki_user_groups` WRITE;
/*!40000 ALTER TABLE `wiki_user_groups` DISABLE KEYS */;
INSERT INTO `wiki_user_groups` VALUES (1,'bureaucrat'),(1,'sysop');
/*!40000 ALTER TABLE `wiki_user_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_user_newtalk`
--

DROP TABLE IF EXISTS `wiki_user_newtalk`;
CREATE TABLE `wiki_user_newtalk` (
  `user_id` int(11) NOT NULL default '0',
  `user_ip` varbinary(40) NOT NULL default '',
  KEY `user_id` (`user_id`),
  KEY `user_ip` (`user_ip`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wiki_user_newtalk`
--

LOCK TABLES `wiki_user_newtalk` WRITE;
/*!40000 ALTER TABLE `wiki_user_newtalk` DISABLE KEYS */;
/*!40000 ALTER TABLE `wiki_user_newtalk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wiki_watchlist`
--

DROP TABLE IF EXISTS `wiki_watchlist`;
CREATE TABLE `wiki_watchlist` (
  `wl_user` int(10) unsigned NOT NULL,
  `wl_namespace` int(11) NOT NULL default '0',
  `wl_title` varchar(255) character set latin1 collate latin1_bin NOT NULL default '',
  `wl_notificationtimestamp` varbinary(14) default NULL,
  UNIQUE KEY `wl_user` (`wl_user`,`wl_namespace`,`wl_title`),
  KEY `namespace_title` (`wl_namespace`,`wl_title`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wiki_watchlist`
--

LOCK TABLES `wiki_watchlist` WRITE;
/*!40000 ALTER TABLE `wiki_watchlist` DISABLE KEYS */;
/*!40000 ALTER TABLE `wiki_watchlist` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2009-10-03  1:19:04
