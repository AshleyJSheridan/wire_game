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
    
		pollingURL              : '/json_feed.php',
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
        gameTime                : 0,
        gameScore               : '0%',

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
        
        getGameStatusData : function(){
            $.ajax({ 
                url: TMW.SiteSetup.pollingURL,
                
                success: function(data){
                    if(TMW.SiteSetup.gameProgress >= 100){
                        data.game_status = false;
						TMW.SiteSetup.gameProgress = 100;
                        console.log('data.game_status: ' + data.game_status);
                    } 

                    if(data.game_status){                        
                        // TESTING DATA START For Testing only must be removed
                        //TMW.SiteSetup.gameProgress  = TMW.SiteSetup.gameProgress + 1;
                        //TMW.SiteSetup.gameTime      = TMW.SiteSetup.gameTime + 3;
                        // TESTING DATA ENDS 
                        
                        // UNCOMMENT FOR REAL DATA VERSION
                        TMW.SiteSetup.gameProgress = data.game_progress;
                        TMW.SiteSetup.gameTime = data.game_time - TMW.SiteSetup.gameStartTime;
                        
                        TMW.SiteSetup.setVideo(); 
                        TMW.SiteSetup.showScore();
                        $('.modalScore').html(TMW.SiteSetup.gameProgress);                     
                    }
                    
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
                    console.log('game_status: ' + gameStatus);
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
					//$('.reveal-modal').trigger('reveal:close');
					$('.modalName').html(data.playerDetails.playerDisplayName);
					$('.modalScore').html(0);
                    TMW.SiteSetup.setPlayerDetails(data.playerDetails); 
                }, 
                dataType: "json", timeout: 30000 }
            );
        },
        
        endGame : function(){
            console.log('endGame');
			TMW.SiteSetup.calculateTotalTime();
            $.ajax({ 
                url:    TMW.SiteSetup.endGameURL, 
                data:   { 
					playerProgress 		: TMW.SiteSetup.gameProgress,
				   playerTime 			: TMW.SiteSetup.gameTime
				},
                
                success: function(data){
                    TMW.SiteSetup.setPlayerDetails(data.playerDetails);
                    TMW.SiteSetup.setScoreBoard(data.scoreBoard);
                }, 
                dataType: "json", complete: TMW.SiteSetup.openGameEndModal, timeout: 30000 }
            );     
        },
                
        setPlayerDetails : function(playerDetails){
            if(playerDetails.twitterhandle){
				playerName = '@' + playerDetails.twitterhandle;
            }
            else{
				playerDetails.twitterhandle = TMW.SiteSetup.defaultTwitterHandle;
				playerName = playerDetails.firstname + ' ' + playerDetails.lastname[0];
			}                   
			//alert(playerDetails.playerDisplayName);
			//alert(playerName);
            
			var playerDataHTML = '<div class="now-playing-img"><img class="now-playing-img" alt="' + playerName + '" src="' + playerDetails.playerTwitterImg + '"><img src="/assets/img/competition/skrews.png" class="skrews" /></div><div class="now-playing-name"><a target="_blank" title="' + playerName +'" href="https://twitter.com/' + playerDetails.twitterhandle + '">' + playerName + '</a></div>'; 
            $('.playerDetails').html(playerDataHTML).show('slow');
        },
                
        setScoreBoard : function(scoreBoard){
            console.log('setScoreBoard');
            var scoreBoardHTML = '';
            
            for (var score in scoreBoard) {
                if(scoreBoard[score].twitterhandle){
                    scoreBoardHTML = scoreBoardHTML + '<li><a href="https://twitter.com/' + scoreBoard[score].twitterhandle + '" title="' + scoreBoard[score].twitterhandle + '" target="_blank" class="username">' + scoreBoard[score].twitterhandle + '</a><span class="userscore">' + scoreBoard[score].playerProgress + '</span><span class="usertime">(' + scoreBoard[score].playerTime + ')</span></li>';          
                }
                else{
                    scoreBoardHTML = scoreBoardHTML + '<li><a href="https://twitter.com/tmwagency" title="' + scoreBoard[score].firstname + ' ' + scoreBoard[score].lastname[0] + '" target="_blank" class="username">' + scoreBoard[score].firstname + ' ' + scoreBoard[score].lastname[0] + '</a><span class="userscore">' + scoreBoard[score].playerProgress + '</span><span class="usertime">(' + scoreBoard[score].playerTime + ')</span></li>';  
                }
            }
            $('.leaderboard ul').html(scoreBoardHTML).show('slow');
        },
                
        setVideo : function(){
            console.log('setVideo'); 
            callToActionscript(TMW.SiteSetup.gameProgress);
        },
                
        showScore : function(){
            console.log('showProgress');
            TMW.SiteSetup.gameScore = TMW.SiteSetup.gameProgress + '%';
            
            $('.score').html(TMW.SiteSetup.gameScore).show('slow');
        },
                
        openGameEndModal : function(){
            console.log('openGameEndModal');
            
            var modalBox = $('#failModal');
            
            if(TMW.SiteSetup.gameProgress >= 100){				
				$('.modalScore').html(100); 
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
            TMW.SiteSetup.gameEndTime       = 0;  
            TMW.SiteSetup.gameStartTime     = 0; 
            
            $.ajax({ 
                url:    TMW.SiteSetup.resetGameURL, 
                data:   { },
                
                success: function(data){
                    TMW.SiteSetup.setPlayerDetails(data.playerDetails);
                    TMW.SiteSetup.setVideo(); 
                    TMW.SiteSetup.showScore();                    
                    setTimeout(function(){                    
                        $('.reveal-modal').trigger('reveal:close');
                    }, 5000);
                }, 
                dataType: "json", timeout: 30000 }
            ); 
        },
        
        calculateTotalTime : function(){
            TMW.SiteSetup.gameTime = TMW.SiteSetup.gameEndTime - TMW.SiteSetup.gameStartTime;
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
