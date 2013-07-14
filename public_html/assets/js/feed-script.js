/*	Author:
		TMW - George Bardis
*/

// ======================================
// === Declare global 'TMW' namespace ===
// ======================================
var TMW = window.TMW || {};

// Create a closure to maintain scope of the '$' and remain compatible with other frameworks
(function($) {

	$(function() {
            TMW.SiteSetup.init();
            
            $('.scroll-pane').jScrollPane({
                            verticalDragMinHeight   : 34,
                            verticalDragMaxHeight   : 34,
                            horizontalDragMinWidth  : 27,
                            horizontalDragMaxWidth  : 27
            });            
    });

	// WINDOW.LOAD
	$(window).load(function() {

	});
        
        /*
	// WINDOW.RESIZE
	$(window).resize(function() {

	});

	*/

})(jQuery);

TMW.SiteSetup = {
    
        twittrFeedURL           : '/competition/wire-game/gettwitterfeed',

        init : function () {
                $.ajax({
                    url: TMW.SiteSetup.twittrFeedURL, 
                    data: { ajaxFeed: true }, 

                    success: function(data){
                        TMW.SiteSetup.displayTwiterFeed(data);
                        TMW.SiteSetup.getTwitterFeedData();
                    }, 
                    dataType: "json"}
                );

                //TMW.SiteSetup.getTwitterFeedData();
        },
                
        getTwitterFeedData : function(){
            setTimeout(function(){
                $.ajax({ 
                    url: TMW.SiteSetup.twittrFeedURL, 
                    data: { ajaxFeed: true }, 
                    
                    success: function(data){
                        TMW.SiteSetup.displayTwiterFeed(data);
                        TMW.SiteSetup.getTwitterFeedData();
                    }, 
                    dataType: "json"}
                    );
                }, 30000);    
        },
        
        displayTwiterFeed : function(twitterFeed){           
            var twitterFeedHTML = '';
            var ifyTweet        = '';
            
            for (var tweet in twitterFeed) {
                
                ifyTweet = TMW.SiteSetup.tweetify.clean(twitterFeed[tweet].text);
                
                twitterFeedHTML = twitterFeedHTML + '<li>' + ifyTweet + '</li>';
            }
            
            $('.twiterFeed ul').html(twitterFeedHTML).show('slow');           
        },        
             
        tweetify: {
            link: function(tweet) {
                return tweet.replace(/\b(((https*\:\/\/)|www\.)[^\"\']+?)(([!?,.\)]+)?(\s|$))/g, function(link, m1, m2, m3, m4) {
                    var http = m2.match(/w/) ? 'http://' : '';
                    return '<a class="twtr-hyperlink" target="_blank" href="' + http + m1 + '">' + ((m1.length > 25) ? m1.substr(0, 24) + '...' : m1) + '</a>' + m4;
                });
            },
            at: function(tweet) {
                return tweet.replace(/\B[@＠]([a-zA-Z0-9_]{1,20})/g, function(m, username) {
                    return '<a target="_blank" class="twtr-atreply" href="http://twitter.com/intent/user?screen_name=' + username + '">@' + username + '</a>';
                });
            },
            list: function(tweet) {
                return tweet.replace(/\B[@＠]([a-zA-Z0-9_]{1,20}\/\w+)/g, function(m, userlist) {
                    return '<a target="_blank" class="twtr-atreply" href="http://twitter.com/' + userlist + '">@' + userlist + '</a>';
                });
            },
            hash: function(tweet) {
                return tweet.replace(/(^|\s+)#(\w+)/gi, function(m, before, hash) {
                    return before + '<a target="_blank" class="twtr-hashtag" href="http://twitter.com/search?q=%23' + hash + '">#' + hash + '</a>';
                });
            },
            clean: function(tweet) {
                return this.hash(this.at(this.list(this.link(tweet))));
            }
        }
};
