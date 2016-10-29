/**
 * Created by Administrator on 2016/10/27.
 */
new Vue({
    el: '#topnav',
    data: {
        home : '首页',
        about : '关于我',
        saylist : '个人日记',
        seolist : 'seo技术',
        weblist : 'web前端',
        gustbook : '留言板',
    }
});

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
        this.fetchItems(this.pagination.current_page);
    },
    methods: {
        fetchItems: function (page) {
            var data = {page: page};

            this.$http.get('getBlog',data).then(function (response) {
                //look into the routes file and format your response
                //console.log(response.data.pagination.current_page);
                this.$set('items', response.data.data);
                this.$set('pagination', response.data.pagination);
            }, function (error) {
                // handle error
            });
        },
        changePage: function (page) {
            this.pagination.current_page = page;
            this.fetchItems(page);
        }
    },
    computed:{
        isActived: function () {
            return this.pagination.current_page;
        },
        pagesNumber: function () {
            if (!this.pagination.to) return [];

            var from = this.pagination.current_page - this.offset;
            if (from < 1) from = 1;
            var to = from + (this.offset * 2);
            if (to >= this.pagination.last_page) to = this.pagination.last_page;

            var pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        }
    }
});
