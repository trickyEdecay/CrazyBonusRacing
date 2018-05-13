-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2018-05-11 15:52:43
-- 服务器版本： 10.1.28-MariaDB
-- PHP Version: 5.6.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `crazy_bonus_racing`
--

-- --------------------------------------------------------

--
-- 表的结构 `question`
--

CREATE TABLE `question` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `a` text,
  `b` text,
  `c` text,
  `d` text,
  `randomtrue` char(1) DEFAULT NULL,
  `peoplelimit` int(11) NOT NULL,
  `addscore` int(11) NOT NULL,
  `minusscore` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `extension` int(11) NOT NULL DEFAULT '0',
  `availabletime` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `question`
--

INSERT INTO `question` (`id`, `question`, `a`, `b`, `c`, `d`, `randomtrue`, `peoplelimit`, `addscore`, `minusscore`, `sort`, `extension`, `availabletime`) VALUES
(9, 'iphone 8 没有哪种机身颜色', '钻石白(白)', '深空灰(灰)', '粉礼金(金)', '光感银(银)', 'D', 200, 5, 0, 61, 0, 10),
(21, '下列哪个选项不是秦岭——淮河线的意义', '1月零度等温线', '我国湿润区和半湿润区分界线', '我国亚热带季风气候与温带季风气候分界线', '400mm等降水量线', 'A', 200, 5, 5, 62, 0, 15),
(22, '以下哪个数距离1最近', '30/31', '7/6', '11/10', '31/30', 'A', 200, 5, 5, 63, 0, 10),
(23, '下列人物中谁不是三国鼎立时期的人物?', '曹操', '孙权', '刘备', '诸葛亮', 'D', 200, 5, 5, 64, 0, 10),
(24, '淘宝买下拍个东西后未付款,多少个小时以后交易会被关闭', '72小时', '2小时', '12小时', '24小时', 'A', 200, 5, 5, 65, 0, 12),
(25, '很多人喜欢将白兔作为宠物来养，那么它的眼球是什么颜色？', '透明无色', '红色', '灰色', '棕色', 'D', 200, 7, 5, 66, 0, 10),
(50, '一条微信朋友圈的图片数量是以下哪个选项时，排版会让“强迫症”受不了？', '5', '3', '4', '1', 'B', 400, 10, 0, 1, 0, 12),
(51, '两个月前的寒假过后，学校是哪一天正式上课(非报到)的呢？', '3月5日', '3月4日', '3月10日', '3月11日', 'D', 120, 20, 7, 16, 0, 12),
(52, '过年时近乎人人玩的支付宝五福红包中，没有以下哪一个“福”？', '诚信福', '富强福', '友善福', '爱国福', 'D', 400, 10, 3, 3, 0, 10),
(53, '3000 分钟是多少小时？', '50', '5', '60', '6', 'D', 140, 10, 3, 4, 0, 8),
(54, '《名侦探柯南》中毛利兰最擅长的运动是？', '空手道', '羽毛球', '足球', '射击', 'B', 250, 10, 5, 5, 0, 10),
(55, '下列哪个地方不是一个首都城市？', '里约热内卢', '平壤', '曼谷', '梵蒂冈城', 'C', 250, 12, 5, 6, 0, 12),
(56, '以下哪一句话的拼音首字母是 emmmmmmm？', '恶魔妈妈买猫面膜', '恶魔妈妈买面膜', '萌萌鹅妈妈买面膜', '鹅妈妈买猫面膜', 'B', 250, 12, 5, 7, 0, 15),
(57, '以下哪句网络流行语的出处不是来源于影视？', '确认过眼神，你是广东人', '这面真香', '打工是不可能打工的', '陈独秀你给我坐下', 'C', 210, 12, 7, 8, 0, 14),
(58, '天线宝宝里哪一个角色的天线是一条直线？', '迪西（绿）', '丁丁（紫）', '拉拉（黄）', '小波（红）', 'D', 190, 12, 7, 9, 0, 9),
(59, '年已近半，今天过后还有多少个法定节假日呢？', '3', '5', '6', '4', 'A', 180, 12, 10, 10, 0, 14),
(60, '周杰伦唱的哪一首歌不是方文山写的词？', '等你下课', '告白气球', '双截棍', '听见下雨的声音', 'C', 170, 15, 5, 11, 0, 12),
(61, '边长为 2 的正方形，它的外接圆半径是？', '2', '4', '√2', '2√2', 'A', 160, 15, 7, 12, 0, 14),
(62, '以下哪种垃圾是可回收垃圾？', '洗洁精瓶子', '卫生纸', '灯泡', '果皮', 'C', 150, 15, 10, 13, 0, 10),
(63, '现在一个月赚超过多少钱时需要缴纳个人所得税？', '3500 元', '3000 元', '4500 元', '3800 元', 'B', 140, 15, 12, 14, 0, 12),
(64, '以下哪种规格的屏幕看起来最长？', '21:9', '16:9', '10:9', '4:3', 'C', 130, 15, 15, 15, 0, 9),
(65, '微软 Office 系列不包含哪个软件？', 'Photoshop', 'Word', 'Excel', 'PowerPoint', 'D', 400, 10, 3, 2, 0, 12),
(66, '以下哪个定律可用于确定电流产生的磁场方向？', '安培定律', '库仑定律', '楞次定律', '法拉第电磁感应定律', 'D', 120, 20, 10, 17, 0, 12),
(67, '小时候，用来玩超级马里奥的红白游戏机手柄上有哪些主要功能按键？', 'A、B', '1、2', 'A、B、X、Y', '△、○、□、×', 'C', 120, 20, 12, 18, 0, 12),
(68, '孤独地吃饭，还被呛住了，如何自救才是正确的？', '将肚子无情地撞向餐桌边缘', '把那碗汤端起来快速地喝下', '干了调料区的那瓶陈年老醋', '捏小拳拳使劲锤自己的背部', 'D', 120, 20, 15, 19, 0, 18),
(69, '以下哪部电影不是2018年奥斯卡最佳影片奖提名？', '寻梦环游记', '三块广告牌', '水形物语', '敦刻尔克', 'D', 120, 20, 17, 20, 0, 12),
(70, '“番茄”没有以下哪个别称？', '藤莓', '狼桃', '爱情苹果', '毛秀才', 'C', 100, 25, 7, 21, 0, 12),
(71, 'iPhone X 屏幕上 “刘海” 的英文单词是？', 'notch', 'bang', 'fringe', 'buzz', 'A', 90, 25, 10, 22, 0, 10),
(72, '关于法式面包，说法错误的是？', '法式面包变硬有利于延长它的保质期', '法式面包是根据一条法律规定做出来的', '法棍的长度必须控制在 65 cm 左右', '制作法棍的面团只能包含面粉、水、酵母和盐', 'A', 80, 25, 12, 23, 0, 20),
(73, '以下哪个是鲁迅先生的作品？', '热风', '凡客', '红蜻蜓', '太平鸟', 'B', 70, 25, 15, 24, 0, 12),
(74, '钢琴键盘上，与 “fa” 间隔 4 个白键的按键发音是什么？', '“re” 或 “la”', '“mi” 或 “sol”', '“do” 或 “xi”', '“fa”', 'A', 60, 25, 17, 25, 0, 25),
(75, '哪辆公交车只经过西校门不经过南校门？', '14', '17', '42', '73', 'A', 50, 25, 20, 26, 0, 9),
(76, '舍友有脚气，给他啥建议？', '补充维生素B', '补充维生素D', '补充维生素E', '补充维生素A', 'D', 50, 25, 22, 27, 0, 10),
(77, '以下哪个字不是中文的大写数字？', '廿', '兆', '零', '億', 'D', 30, 25, 25, 28, 0, 12),
(78, '与其他选项不一样的人工智能是？', '微软小冰', 'Siri', '小爱同学', 'Bixby', 'D', 30, 27, 25, 29, 0, 12),
(79, '我们的赞助商 “六号正装” 在微信公众号中所使用的一句口号是？', '知你需要，在你身边', '男女正装，天下无双', '青春活力，风采尽显', '精彩人生，从这里开始', 'B', 80, 30, 7, 30, 0, 20),
(80, '科技社团哪家棒', '嘉应大学找科创', '中国山东找蓝翔', '厨师就选新东方', '北大青鸟等你来', 'D', 400, 0, 0, 0, 0, 20);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `question`
--
ALTER TABLE `question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
