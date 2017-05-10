<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>计分细则 - 全民答疯抢</title>

    <!-- build:js /assets/js/main.js -->
    <script src="/front/assets/js/jquery-1.12.4.js"></script>
    <!-- endbuild -->

    <!-- build:css /assets/css/main.css -->
    <link href="/front/assets/style/font-awesome.css" rel="stylesheet">
    <link href="/front/assets/style/user/score-rules.less" rel="stylesheet">
    <!-- endbuild -->
</head>
<body>
    <header>
        <h1><span class="fa fa-question-circle-o"></span>计分细则</h1>
    </header>

    <main>
        <section>
            <h1>问题属性</h1>
            <img src="/front/assets/img/score-rules-1.png">
            <p>针对每一道题，我们都为问题设置了四个属性：</p>
            <ul>
                <li><strong>作答时间：</strong>每一道题必须在规定的时间内作答完成。</li>
                <li><strong>幸运儿人数：</strong>只有幸运儿才能获得加分。</li>
                <li><strong>题目加分：</strong>幸运儿获得的分数。</li>
                <li><strong>题目扣分：</strong>部分人将被扣除的分数。</li>
            </ul>
        </section>

        <section>
            <h1>抢答排序</h1>
            <img src="/front/assets/img/score-rules-2.png">
            <p>当屏幕上出现验证码开始，您进入题目和提交答案的时间将被记录并进行排序，由此得到的排序我们称之为<strong>答题排序</strong>。</p>
        </section>

        <section>
            <h1>答对加分规则</h1>
            <img src="/front/assets/img/score-rules-3.png">
            <p>每一道题都规定了<strong>幸运儿人数</strong>，答对的人之中<strong>依照抢答排序</strong>，只有题目规定人数以内的玩家能获得加分。所以即使答对了，但是手速太慢是有可能不加分的哦。</p>
        </section>

        <section>
            <h1>手速太慢要扣分的哦</h1>
            <img src="/front/assets/img/score-rules-4.png">
            <p>智慧和敏捷缺一不可，当您在<strong>抢答排序</strong>中排在了全场 <span class="red">15%</span> 的人以后，将扣除<strong>题目规定的扣分</strong>。</p>
        </section>

        <section>
            <h1>只要我快是不是就可以不扣分</h1>
            <img src="/front/assets/img/score-rules-5.png">
            <p>答题是一种追求准确率的行为，我们不希望任何人为了规避扣分而忽略了答题本身的意义。</p>
            <p>凡是答错，都会扣除自身分数的<span class="red">5%</span>。</p>
            <p>当然，这个扣分不和上一点中的扣分叠加，您不会在一道题中被扣两次分数。</p>
        </section>

        <section>
            <h1>挂机行为扣分原则</h1>
            <p>消极比赛对于其他玩家来说是一种不公平的行为。</p>
            <p>因此，只要您没有进行答题，系统会扣除本题目规定<strong>扣分分数</strong>的<span class="red">1.5倍</span>。</p>
        </section>

        <section>
            <h1>同分排序原则</h1>
            <img src="/front/assets/img/score-rules-6.png">
            <p>如果您与朋友的分数一致，但是排序比他靠后，那么说明，您的朋友达到这个分数的时间比您要早。</p>
            <p>系统将依照最后一次您达到这个分数的时间排序。</p>
        </section>
    </main>

    <footer>
        <div class="spliter"></div>
        <p>&nbsp;&nbsp;&nbsp;&nbsp;我们致力于净化游戏环境，提升游戏体验，每年都在规则上做出相应的修改和尝试以达到更棒的效果，如果您觉得我们的规则还有疏漏或是不足、甚至有更棒的想法，欢迎反馈给我们的公众号：<span class="underline">嘉应科创社</span> 。</p>
        <p>&nbsp;&nbsp;&nbsp;&nbsp;谢谢您的支持。祝您在游戏中取得好成绩。</p>
    </footer>
</body>
</html>