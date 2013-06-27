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
        gameTime                : 0,

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
                }, 3000000);    
        },
        
        displayTwiterFeed : function(twitterFeed){           
            var twitterFeedHTML = '';
            for (var tweet in twitterFeed) {
                twitterFeedHTML = twitterFeedHTML + '<li>' + twitterFeed[tweet].text + '</li>';
            }
            
            $('.twiterFeed ul').html(twitterFeedHTML).show('slow');           
        },
        
        getGameStatusData : function(){
            $.ajax({ 
                url: TMW.SiteSetup.pollingURL,
                
                success: function(data){
                    TMW.SiteSetup.checkGameStatusChange(data.game_status, data.RFHandleId);
                }, 
                dataType: "json", complete: TMW.SiteSetup.getGameStatusData, timeout: 30000 }
            );
            //}, dataType: "json", timeout: 30000 });
        },
        
        checkGameStatusChange : function(gameStatus, playerRFHandleId){
    
                if(gameStatus == TMW.SiteSetup.gameStatusFlag){
                    return;
                }
                else if(gameStatus != TMW.SiteSetup.gameStatusFlag){
                    TMW.SiteSetup.gameStatusFlag = gameStatus;
                    TMW.SiteSetup.playerRFHandleId = playerRFHandleId; 
                    console.log(gameStatus);
                    if(gameStatus){
                        TMW.SiteSetup.startGame();
                    }
                    else{
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
            TMW.SiteSetup.resetGame();
        },
                
        resetGame : function(){
            console.log('resetGame');
            TMW.SiteSetup.playerRFHandleId  = null;  
            TMW.SiteSetup.gameStatusFlag    = false;  
            TMW.SiteSetup.gameProgress      = 0;  
            TMW.SiteSetup.gameProgress      = 0; 
            
            $.ajax({ 
                url:    TMW.SiteSetup.resetGameURL, 
                data:   { },
                
                success: function(data){
                    TMW.SiteSetup.setPlayerDetails(data.playerDetails);
                }, 
                dataType: "json", timeout: 30000 }
            ); 
        },
                
        postToTwitter : function(){
            console.log('postToTwitter');     
        }

};
