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
	});

	/* optional triggers

	// WINDOW.LOAD
	$(window).load(function() {
            swfobject.registerObject("scrubber", "9.0.0", "expressInstall.swf");

	});

	// WINDOW.RESIZE
	$(window).resize(function() {

	});

	*/

})(jQuery);

TMW.SiteSetup = {
    
	pollingURL              : '/competition/wire-game/getmotiondata',
        startGameURL            : '/competition/wire-game/gamestart',
        endGameURL              : '/competition/wire-game/gameend',
        twittrFeedURL           : '/competition/wire-game/gettwitterfeed',
        resetGameURL            : '/competition/wire-game/resetuserdetails',
        defaultTwitterHandle    : 'tmwagency',
        playerRFHandleId        : null,
        gameStatusFlag          : false,
        gameProgress            : 0,
        gameStartTime           : 0,
        gameEndTime             : 0,

	init : function () {
            TMW.SiteSetup.getGameStatusData();
            TMW.SiteSetup.getTwitterFeedData();
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
                }, 300000);    
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
        
        getGameStatusData : function(){
            $.ajax({ 
                url: TMW.SiteSetup.pollingURL,
                
                success: function(data){
                    //TMW.SiteSetup.gameProgress = data.game_progress;
                    // For Testing only must be removed
                    TMW.SiteSetup.gameProgress = TMW.SiteSetup.gameProgress++;
                    callToActionscript(TMW.SiteSetup.gameProgress);
                    if(TMW.SiteSetup.gameProgress == 100){
                        data.game_status = false;
                    }
                    ///
                    TMW.SiteSetup.checkGameStatusChange(data.game_status, data.RFHandleId, data.game_time);
                }, 
                dataType: "json", complete: TMW.SiteSetup.getGameStatusData, timeout: 30000 }
            );
            //}, dataType: "json", timeout: 30000 });
        },
        
        checkGameStatusChange : function(gameStatus, playerRFHandleId, gameTime){
    
                if(gameStatus == TMW.SiteSetup.gameStatusFlag){
                    return;
                }
                else if(gameStatus != TMW.SiteSetup.gameStatusFlag){
                    TMW.SiteSetup.gameStatusFlag = gameStatus;
                    TMW.SiteSetup.playerRFHandleId = playerRFHandleId; 
                    console.log(gameStatus);
                    if(gameStatus){
                        TMW.SiteSetup.gameStartTime = gameTime;
                        console.log(TMW.SiteSetup.gameStartTime);
                        TMW.SiteSetup.startGame();
                    }
                    else{
                        TMW.SiteSetup.gameEndTime = gameTime;
                        console.log(TMW.SiteSetup.gameEndTime);
                        TMW.SiteSetup.endGame();                        
                    }
                }            
        },
        
        startGame : function(){
            console.log('startGame');
            $.ajax({ 
                url:    TMW.SiteSetup.startGameURL, 
                data:   { playerRFHandleId: TMW.SiteSetup.playerRFHandleId },
                
                success: function(data){
                    $('.reveal-modal').trigger('reveal:close');
                    TMW.SiteSetup.setPlayerDetails(data.playerDetails);
                }, 
                dataType: "json", timeout: 30000 }
            );
        },
        
        endGame : function(){
            console.log('endGame');
            $.ajax({ 
                url:    TMW.SiteSetup.endGameURL, 
                data:   { },
                
                success: function(data){
                    TMW.SiteSetup.setPlayerDetails(data.playerDetails);
                    TMW.SiteSetup.setScoreBoard(data.scoreBoard);
                }, 
                dataType: "json", complete: TMW.SiteSetup.openGameEndModal, timeout: 30000 }
            );     
        },
                
        setPlayerDetails : function(playerDetails){
            if(!playerDetails.twitterhandle){
                playerDetails.twitterhandle = TMW.SiteSetup.defaultTwitterHandle;
                playerName = playerDetails.firstname;
            }
            else{
                playerName = playerDetails.twitterhandle;
            }
            var playerDataHTML = '<img class="now-playing-img" alt="' + playerName + '" src="' + playerDetails.playerTwitterImg + '"><div class="now-playing-name"><a target="_blank" title="' + playerName +'" href="https://twitter.com/' + playerDetails.twitterhandle + '">' + playerName + '</a></div>'; 
            $('.playerDetails').html(playerDataHTML).show('slow');
        },
                
        setScoreBoard : function(scoreBoard){
            console.log('setScoreBoard');
            var scoreBoardHTML = '';
            
            for (var score in scoreBoard) {
                if(scoreBoard[score].twitterhandle){
                    scoreBoardHTML = scoreBoardHTML + '<li><a href="https://twitter.com/' + scoreBoard[score].twitterhandle + '" title="' + scoreBoard[score].twitterhandle + '" target="_blank" class="username">' + scoreBoard[score].twitterhandle + '</a><span class="userscore">' + scoreBoard[score].playerScore + '</span></li>';          
                }
                else{
                    scoreBoardHTML = scoreBoardHTML + '<li><a href="https://twitter.com/tmwagency" title="' + scoreBoard[score].firstname + ' ' + scoreBoard[score].lastname.charAt(0) + '" target="_blank" class="username">' + scoreBoard[score].firstname + ' ' + scoreBoard[score].lastname.charAt(0) + '</a><span class="userscore">' + scoreBoard[score].playerScore + '</span></li>';  
                }
            }
            $('.leaderboard ul').html(scoreBoardHTML).show('slow');
        },
                
        setVideo : function(){
            console.log('setVideo');     
        },
                
        openGameEndModal : function(){
            console.log('openGameEndModal');
            
            var modalBox = $('#failModal');
            
            if(TMW.SiteSetup.gameProgress == 100){
                modalBox = $('#winnerModal'); 
            }
            
            $(modalBox).reveal({
                animation:              'fadeAndPop',
                animationspeed:         600,
                closeonbackgroundclick: true,
                dismissmodalclass:      'close-reveal-modal',
                close:                  TMW.SiteSetup.resetGame()                    
                }
            ); 
        },
                
        resetGame : function(){
            console.log('resetGame');
            TMW.SiteSetup.playerRFHandleId  = null;  
            TMW.SiteSetup.gameStatusFlag    = false;  
            TMW.SiteSetup.gameProgress      = 0;  
            TMW.SiteSetup.gameTime          = 0; 
            
            $.ajax({ 
                url:    TMW.SiteSetup.resetGameURL, 
                data:   { },
                
                success: function(data){
                    TMW.SiteSetup.setPlayerDetails(data.playerDetails);
                }, 
                dataType: "json", timeout: 30000 }
            ); 
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
