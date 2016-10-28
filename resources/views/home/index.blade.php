<!doctype html>
<html>
<head>
    <meta charset="gb2312">
    <title>个人博客</title>
    <meta name="keywords" content="个人博客模板,博客模板" />
    <meta name="description" content="优雅、稳重、大气,低调。" />
    <link href="css/index.css" rel="stylesheet">
    <link href="css/home.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <!--[if lt IE 9]>
    <script src="js/html5.js"></script>
    <![endif]-->
    <script src="{{asset('js/vue.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/vue-resource.min.js')}}" type="text/javascript"></script>
</head>
<body>
<header>
    <div id="logo"><a href="/"></a></div>
    <nav class="topnav" id="topnav">
        <a href="index.html"><span>@{{ index }}</span><span class="en">Home</span></a>
        <a href="about.html"><span>@{{ about }}</span><span class="en">About</span></a>
        </a><a href="saylist.html"><span>@{{ saylist }}</span><span class="en">Diary</span></a>
        <a href="seolist.html"><span>@{{ seolist }}</span><span class="en">Seo</span></a>
        <a href="weblist.html"><span>@{{ weblist }}</span><span class="en">Web</span></a>
        <a href="gustbook.html"><span>@{{ gustbook }}</span><span class="en">Gustbook</span></a>
    </nav>
</header>
<!--end header-->
<div class="banner">
    <section class="box">
        <ul class="texts">
            <p class="p1">纪念我们:</p>
            <p class="p2">终将逝去的青春</p>
            <p class="p3">By:少年</p>
        </ul>
    </section>
</div>
<!--end banner-->
<article>
    <h2 class="title_tj">
        <p>博主<span>推荐</span></p>
    </h2>
    <div class="bloglist left" id="blog">
        <!--wz-->
        <div class="wz" v-for="item in items">
            <h3>@{{ item.title }}</h3>
            <p class="dateview"><span>2013-11-04</span><span>作者：@{{ item.auth }}</span><span>分类：[<a href="#">@{{ item.name }}</a>]</span></p>
            <figure><img src="images/001.jpg"></figure>
            <ul>
                <p>@{{ item.body }}...</p>
                <a title="阅读全文" href="/" target="_blank" class="readmore">阅读全文>></a>
            </ul>
            <div class="clear"></div>
        </div>
        <!--end-->
        <nav id="page">
            <ul class="pagination">
                <li v-if="pagination.current_page > 1">
                    <a href="#" aria-label="Previous"
                       @click.prevent="changePage(pagination.current_page - 1)">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="active"><a href="#">1</a></li>
                <li v-for="page in pagesNumber" v-bind:class="[ page == isActived ? 'active' : '']">
                    <a href="#" @click.prevent="changePage(page)">@{{ page }}</a>
                </li>
                <li v-if="pagination.current_page < pagination.last_page">
                    <a href="#" aria-label="Next"
                       @click.prevent="changePage(pagination.current_page + 1)">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <!--right-->
    <aside class="right">
        <div class="my">
            <h2>关于<span>博主</span></h2>
            <img src="images/my.jpg" width="200" height="200" alt="博主">
            <ul>
                <li>博主：少年</li>
                <li>职业:web前端、网站运营</li>
                <li>简介：一个不断学习和研究，web前端和SEO技术的90后草根站长.</li>
                <li></li>
            </ul>
        </div>
        <div class="bdsharebuttonbox"><a href="#" class="bds_more" data-cmd="more"></a><a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a><a href="#" class="bds_renren" data-cmd="renren" title="分享到人人网"></a><a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a></div>
        <div class="news">
            <h3 class="ph">
                <p>点击<span>排行</span></p>
            </h3>
            <ul class="paih">
                <li><a href="/" title="如何建立个人博客" target="_blank">如何建立个人博客</a></li>
                <li><a href="/" title="html5标签" target="_blank">html5标签</a></li>
                <li><a href="/" title="一个网站的开发流程" target="_blank">一个网站的开发流程</a></li>
                <li><a href="/" title="做网站到底需要什么?" target="_blank">做网站到底需要什么?</a></li>
                <li><a href="/" title="企业做网站具体流程步骤" target="_blank">企业做网站具体流程步骤</a></li>
            </ul>
            <h3>
                <p>用户<span>关注</span></p>
            </h3>
            <ul class="rank">
                <li><a href="/" title="如何建立个人博客" target="_blank">如何建立个人博客</a></li>
                <li><a href="/" title="一个网站的开发流程" target="_blank">一个网站的开发流程</a></li>
                <li><a href="/" title="关键词排名的三个时期" target="_blank">关键词排名的三个时期</a></li>
                <li><a href="/" title="做网站到底需要什么?" target="_blank">做网站到底需要什么?</a></li>
                <li><a href="/" title="关于响应式布局" target="_blank">关于响应式布局</a></li>
                <li><a href="/" title="html5标签" target="_blank">html5标签</a></li>
            </ul>

            <h3 class="links">
                <p>友情<span>链接</span></p>
            </h3>
            <ul class="website">
                <li><a href="#">个人博客</a></li>
                <li><a href="wwww.duanliang920.com">段亮博客</a></li>
            </ul>
        </div>
</article>
<footer>
    <p><span>Design By:<a href="www.duanliang920.com" target="_blank">段亮</a></span><span>网站地图</span><span><a href="/">网站统计</a></span></p>
</footer>
<script src="js/nav.js"></script>
{{--<script src="{{asset('js/home.js')}}" type="text/javascript"></script>--}}
<script type="text/javascript">
    new Vue({
        el: '#blog',
        data: {
            pagination: {
                total: 0,
                per_page: 7,
                from: 1,
                to: 0,
                current_page: 1
            },
            offset: 4,// left and right padding from the pagination <span>,just change it to see effects
            items: []
        },
        ready:function(){
            this.fetchItems(this.current_page);
        },
        methods: {
            fetchItems: function (page) {
                var data = {page: page};
                this.$http.get('getBlog', data).then(function (response) {
                    //look into the routes file and format your response
                    console.log(response.data.pagination.current_page);
                    this.$set('items', response.data.data);
                    this.$set('pagination', response.data.pagination);
                }, function (error) {
                    // handle error
                });
            },
            changePage: function (page) {
                this.current_page = page;
                this.fetchItems(page);
            }
        },
        computed:{
            isActived: function () {
                return this.pagination.current_page;
            },
            pagesNumber: function () {
                if (!this.pagination.to) {
                    return [];
                }
                var from = this.pagination.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }
                var to = from + (this.offset * 2);
                if (to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }
                var pagesArray = [];
                while (from <= to) {
                    pagesArray.push(from);
                    from++;
                }

                return pagesArray;
            }
        }
    });
</script>
</body>
</html>
