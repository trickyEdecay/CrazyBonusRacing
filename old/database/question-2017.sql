-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2017-05-13 08:14:25
-- 服务器版本： 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `667332`
--

-- --------------------------------------------------------

--
-- 表的结构 `question`
--

CREATE TABLE IF NOT EXISTS `question` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=112 ;

--
-- 转存表中的数据 `question`
--

INSERT INTO `question` (`id`, `question`, `a`, `b`, `c`, `d`, `randomtrue`, `peoplelimit`, `addscore`, `minusscore`, `sort`, `extension`, `availabletime`) VALUES
(82, '前段时间火爆神曲《PPAP》中没有以下哪句歌词？', 'I have a pineapple', 'I have a pen', 'Uhh ~ pineapple pen', 'pen pineapple apple pen', 'C', 310, 5, 0, 1, 0, 13),
(83, '以下关于微信单方面拉黑他人的说法正确的是？', '能看到微信运动关注的记录', '能像未加好友一样能看到对方发的朋友圈（对方权限得当）', '能在微信游戏列表中能看到对方', '对方能收到你的转账但是不能收到你的留言', 'D', 310, 5, 2, 2, 0, 11),
(84, '如果说光的红色是FF0000，蓝色是0000FF，绿色是00FF00，那么黄色是？', 'FFFF00', 'FF00FF', '00FFFF', 'F0F0F0', 'D', 300, 7, 4, 3, 0, 10),
(85, '明天就是母亲节了，请问以下哪种花在我国被视为母亲花？', '萱草花', '康乃馨', '牡丹花', '紫荆花', 'A', 300, 7, 4, 4, 0, 10),
(86, '“鹅鹅鹅，曲项向天歌……” 这首古诗的作者是谁？', '骆宾王', '洛宾王', '骆滨王', '洛滨王', 'A', 300, 7, 4, 5, 0, 10),
(87, '早上 9:00-11:00 代表的是十二时辰中的哪个时辰？', '巳时', '卯时', '寅时', '午时', 'A', 290, 8, 4, 6, 0, 10),
(88, '“老司机~带带我……” 歌中的我想让老司机带去什么地方？', '昆明', '云南', '西藏', '成都', 'C', 270, 9, 5, 7, 0, 9),
(89, '在我校，优秀毕业论文的查重率必须低于？', '25%', '28%', '30%', '22%', 'C', 280, 10, 5, 8, 0, 9),
(90, '下列关于近期网络流行语正确的是？', '皮皮虾，我们走~这个梗来源于 “游戏王YGOcore”', '“对方不想跟你说话并向你扔了XX”的原始梗中XX指的是狗', '“扎心了老铁”中“老铁”一词源于老北京', '“向黑恶势力低头” 一梗源于 “向口红势力低头”', 'C', 250, 11, 6, 9, 0, 12),
(91, '一元二次函数的抛物线顶点坐标是以下哪个？', '-b/2a , (4ac-b²)/4a', 'b/2a , (b²-4ac)/4a', '(4ac-b²)/4a , b/2a', '(4ac-b²)/4a , -b/2a', 'A', 240, 11, 7, 10, 0, 13),
(92, '以下哪个官场称谓不合乎官场礼仪（以人民的名义为例）？', '人民群众称李达康（市委书记）为 “达康书记”', '陈岩石（老监察局局长）称沙瑞金（省委书记）为 “小金子”', '沙瑞金（省委书记）称侯亮平（反贪局局长）为 “小侯”', '李达康（市委书记）在公众场合称沙瑞金（省委书记）为 “沙书记”', 'B', 220, 12, 8, 11, 0, 15),
(93, '桌游《米勒山谷的狼人》至今为止发行过的游戏扩充卡牌不包括以下哪个？', '《米勒山谷的狼人：丛林》', '《米勒山谷的狼人：村庄》', '《米勒山谷的狼人：角色》', '《米勒山谷的狼人：新月》', 'B', 200, 12, 9, 12, 0, 10),
(94, '以下哪个国家的国土面积最大？', '日本', '英国', '德国', '意大利', 'B', 180, 12, 9, 13, 0, 10),
(95, 'iPhone 4 及以前所使用的充电线接口叫什么名字？', 'Thirty Pin', 'Lighting', 'Thunderbolt', 'MicroUsb type-B', 'B', 170, 13, 10, 14, 0, 10),
(96, '哆啦A梦喜欢吃铜锣烧，那么哆啦A梦的妹妹多啦美喜欢吃什么？', '菠萝包', '肉松卷', '甜甜圈', '千层饼', 'C', 160, 14, 10, 15, 0, 10),
(97, '以下哪个说法是正确的？', '花露水和酒一样，放置越久会越发地香。', '冬天往玻璃杯里倒水，如果杯壁太薄了就容易爆裂。', '豆腐干和花生米一起吃会吃出爆米花的味道。', '米饭放凉了吃可以预防结肠癌。', 'C', 150, 16, 11, 16, 0, 12),
(98, '本月十九号是星期几？快！', '星期五', '星期四', '星期三', '星期六', 'D', 140, 18, 13, 17, 0, 4),
(99, '史上第一款内置了关卡编辑器的游戏是？', '淘金者（Lode Runner）', '坦克大战（Battle City）', '越野机车（Excitebike）', '炸弹人（Bomberman）', 'B', 130, 19, 13, 18, 0, 10),
(100, '以下哪部电影的上映时间最迟？（依照美国定档时间）', '阿凡达2', '驯龙高手3', '复仇者联盟3', '神奇动物在哪里2', 'B', 120, 20, 14, 19, 0, 10),
(101, '梅州火车站没办法直达以下哪个火车站？', '银川站', '化州站', '阳新站', '南宁站', 'B', 100, 21, 17, 21, 0, 12),
(102, '以下哪种咖啡的制作中通常不加牛奶？', '土耳其咖啡', '摩卡', '白咖啡', '卡布奇诺', 'D', 110, 20, 15, 20, 0, 10),
(103, '老刘因工作原因，回家后经常殴打妻子小李，小李无奈经常哭诉隔壁未婚老王，日久生情，现小李想离婚，以下正确的是？', '如小李和老王同居，则小李不能向老刘主张损害赔偿。', '如小李和老王同居，则老刘能向小李主张损害赔偿。', '针对这种家庭暴力，小李不能向老刘主张损害赔偿。', '针对这种家庭暴力，小李不能向老刘主张精神损害赔偿。', 'D', 90, 22, 18, 22, 0, 18),
(104, '假设本题目有40%的人答对了，两个主持人 各 随机找一名观众，则他们找到的观众 对错情况 不一样的概率是？', '0.48', '0.24', '0.5', '0.25', 'A', 80, 23, 19, 23, 0, 20),
(105, '「If Winter comes, can Spring be far behind？」这首诗构思的是哪里的景象？', '阿诺河畔的一片树林', '佛罗伦萨的一个小镇', '泰晤士河的一片村庄', '摩尔多瓦的一片平原', 'B', 70, 24, 20, 24, 0, 15),
(106, '以下哪个选项中没有单词拼错？', 'pronunciation maintenance ninth professor', 'excellent convinence occurred recommend', 'temperature thursday slience default', 'environment embarrassed sponser principal', 'C', 60, 25, 20, 25, 0, 14),
(107, '大家心目中都有小目标，小目标肯定是和现实有一定的差距，而为了缩短差距而想办法这种行为在心理学上称为？', '手段-目的分析法', '行为-状态分析法', '爬山法', '尝试错误法', 'D', 50, 26, 20, 26, 0, 10),
(108, '我校世纪广场有多少片小草坪？', '32', '40', '28', '36', 'C', 40, 27, 20, 27, 0, 10),
(109, '以下关于 Wifi 路由器说法错误的是？', '理论上一台路由器最多接入254个设备', '路由器能一边发射wifi信号一边接收wifi信号 ', '2.4 GHz 的工作频段支持 1-13 的信道设置', 'IEEE 802.11ac 协议只能运行在 5 GHz 的工作频段上', 'B', 30, 28, 20, 28, 0, 10),
(110, '“大豆” 在五谷中称为？', '菽', '黍', '稷', '寇', 'A', 20, 29, 20, 29, 0, 10),
(111, '本活动赞助商 “寻券叭” 公众号提供的菜单中不包含哪一项？', '淘宝大秒杀', '9.9 秒杀', '聚划算专属优惠页', '优惠券清单', 'A', 15, 30, 20, 30, 0, 10);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `question`
--
ALTER TABLE `question`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=112;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
