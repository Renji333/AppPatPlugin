jQuery(document).ready(function($) {

    $(".Loadable").click(function() {
        var $btn = $(this);
        $btn.button('loading');
    });

    $( ".selectPost" ).each(function( index ) {

        var type = $(this).attr("data-type");

        $( this ).selectpicker({
            style : 'btn-default btnLinks '+type,
        });

    });

    $('div.bootstrap-select .btnLinks').next().find( 'ul.dropdown-menu li').click(function() {
        var id = $(this).attr('data-original-index') ;
        var type = $(this).parent().parent().prev().attr('class');
        type = type.split(" ");
        type = type.slice(-1)[0];
        var el = $('.selectPost[data-type="'+type+'"] option').get(parseInt(id));
        $(el).click();
    });

    $('.selectPost[data-type="GET"] option').click(function() {

        var id = $(this).attr('value'), url = $(this).attr('data-guid'), txt = $(this).text() ;

        $('#CurrentArticle').css('display','block');
        $('#AddPatLink').css('display','block');

        $('.selectPost[data-type="NLP"]').removeAttr("disabled");
        $('.selectPost[data-type="PAT"]').removeAttr("disabled");

        $('.btn.dropdown-toggle.disabled.bs-placeholder.btn-default.btnLinks.NLP').attr("class","btn dropdown-toggle bs-placeholder btn-default btnLinks NLP");
        $('.btn.dropdown-toggle.disabled.bs-placeholder.btn-default.btnLinks.DOS').attr("class","btn dropdown-toggle bs-placeholder btn-default btnLinks DOS");

        $('#AddPatLinkId').val(id);
        $('#TitleCurrentArticle').text(txt);
        $('#SeeCurrentArticle').attr('href',url);
        $('#EditCurrentArticle').attr('href',"post.php?post="+id+"&action=edit");

        getLinks(id,"PAT");
        getLinks(id,"NLP");
        getLinks(id,"DOS");

    });

    $('.selectPost[data-type="NLP"] option, .selectPost[data-type="PAT"] option').click(function() {

        var idPostInLink = $(this).attr('value'), idPost = parseInt($('#AddPatLinkId').val()), type = $(this).attr("data-type");

        jQuery.ajax({
            url: 'admin-ajax.php',
            data:{
                'action': 'AddLinks',
                'idPostInLink':idPostInLink,
                'type':type,
                'idPost':idPost
            },
            success:function(data){
                getLinks(idPost,type);
            },error: function(errorThrown){
                alert('error selectPostAdd');
            }
        });

    });

    function getLinks(id,type){

        jQuery.ajax({

            url: 'admin-ajax.php',
            data:{
                'action':'getLinks',
                'id':id,
                'type':type
            },
            dataType: 'JSON',
            success:function(data){
                InsertTbody(data,type);
            },error: function(errorThrown){
                alert('error getLinks');
            }

        });

    }

    function InsertTbody(data,type){

        var dom = "";


            data.forEach(function(element) {

                if(element['title'] == '' || element['title'] == null){
                    element['title'] = element['post_title'];
                }

                dom = dom + '<tr><th scope="row"><strong>'+element['id']+'</strong></th>';
                dom = dom + '<td><strong><a href="'+element['guid']+'" target="_blank">'+element['title']+'</a></strong></td>';
                dom = dom + '<td class="TdDelLink"><button data-del="'+element['idDel']+'" data-type="'+type+'" class="btn btn-danger DelLink">Supprimer</a></td></tr>';

            });



        $(".tbodyLink[data-type='"+type+"']").html(dom);

    }


    $('.tbodyLink').on('click', '.DelLink', function(){

        var id = parseInt($(this).attr('data-del')), idPost = parseInt($('#AddPatLinkId').val()), type = $(this).attr("data-type");

        jQuery.ajax({
            url: 'admin-ajax.php',
            data:{
                'action':'DelLinks',
                'id':id,
                'type':type
            },
            success:function(data){
                getLinks(idPost,type);
            },error: function(errorThrown){
                alert('error tbodyLink');
            }
        });

        return false;

    });



    $('#AddPatLinkBtnSave').click(function() {

        var id = parseInt($('#AddPatLinkId').val()), txt = $('#AddPatLinkTitle').val(), url = $('#AddPatLinkPage').val(), type = "PAT";

        jQuery.ajax({
            url: 'admin-ajax.php',
            data:{
                'action':'AddLinks',
                'id':id,
                'txt':txt,
                'url':url,
                'type':type
            },
            success:function(data){

                getLinks(id,type);

            },error: function(errorThrown){
                alert('error btn_save');
            }
        });

        return false;

    });

});
