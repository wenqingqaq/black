//实例化完整编辑器
var editor_options = {
	zIndex : 9100,
    imageUrl:CONFIG.imageUrl,
    imagePath:"",

    scrawlUrl:CONFIG.scrawlUrl,
    scrawlPath:"",

    fileUrl:CONFIG.fileUrl,
    filePath:"",
    
    wordImageUrl:CONFIG.imageUrl,
    wordImagePath:"", //

    getMovieUrl:UEDITOR_HOME_URL + "../yunserver/getMovie.php",

    lang:/^zh/.test(navigator.language || navigator.browserLanguage || navigator.userLanguage) ? 'zh-cn' : 'en',
    langPath:UEDITOR_HOME_URL + "lang/",
	toolbars:[
    ['fullscreen', 'source', '|', 'undo', 'redo', '|',
        'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
        'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
        'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
        'directionalityltr', 'directionalityrtl', 'indent', '|',
        'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
        'link', 'unlink', 'anchor', '|', 'imagenone', 'imageleft', 'imageright', 'imagecenter', '|',
        'insertimage', 'emotion', 'scrawl', 'insertvideo', 'music', 'attachment', 'map', 'gmap', 'insertframe','insertcode', 'webapp', 'pagebreak', 'template', 'background', '|',
        'horizontal', 'date', 'time', 'spechars', 'snapscreen', 'wordimage', '|',
        'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', '|',
        'print', 'preview', 'searchreplace']
    ]
};
//实例化简单编辑器
var editor_options_Simple = {
	zIndex : 9100,
    imageUrl:CONFIG.imageUrl,
    imagePath:"",

    scrawlUrl:CONFIG.scrawlUrl,
    scrawlPath:"",

    fileUrl:CONFIG.fileUrl,
    filePath:"",
    
    wordImageUrl:CONFIG.imageUrl,
    wordImagePath:"", //

    getMovieUrl:UEDITOR_HOME_URL + "../yunserver/getMovie.php",

    lang:/^zh/.test(navigator.language || navigator.browserLanguage || navigator.userLanguage) ? 'zh-cn' : 'en',
    langPath:UEDITOR_HOME_URL + "lang/",
	toolbars:[['FullScreen', 'Source', 'Undo', 'Redo','|',
	'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain'
	]]
};